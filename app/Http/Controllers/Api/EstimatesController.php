<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App;
use App\Estimate;
use App\EstimateDetail;
use App\Customer;
use App\Product;
use App\Http\Requests\StoreEstimateRequest;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EstimatesController extends Controller
{
	public function __construct()
	{
        // API jwt-auth でのログインユーザーのチェック
        if (Auth::guard('api')->check() == true) {
            $this->middleware('auth:api', ['except' => ['login']]);
        } else {
            $this->middleware('auth');
        }

//        $this->middleware(function ($request, $next) {
//            $accept_language = $request->header('Accept-Language');
//            // 言語の切り替え
//            App::setLocale($accept_language);
//
//            return $next($request);
//        });
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$search_word = $request->input('search_word');
        $page_rows = $request->input('page_rows');
        $rows = ($page_rows == null) ? 15 : $page_rows;

		$estimates = Estimate::searchWordFilter($search_word)->orderBy('created_at', 'desc')->paginate($rows);
        $estimates_count = $estimates->count();

        // レコードの存在をチェック
        if ($estimates_count == 0) {
            return response()->json([
                'message' => __('Data Not Found.'),
                'data' => $estimates
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => __('Displaying :counts item',['counts' => $estimates_count]),
                'data' => $estimates
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$estimate = new Estimate;
		$estimate_details = [];
		$customers = Customer::all();
		$products = Product::all();
		$row_counts = 10;

		// dynamic add detail old inputs
		$dynamic_add_details = [];
		if (old('details')) {
			foreach (old('details') as $key => $detail) {
				if (is_numeric($key) && $key < 0) {
					$dynamic_add_details[] = $detail;
				}
			}
		}

		// set tax rate
		$estimate->tax_rate = Estimate::DEFAULT_TAX_RATE;

		return view('estimates.create', compact('estimate', 'estimate_details', 'customers', 'products', 'row_counts', 'dynamic_add_details'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEstimateRequest $request)
    {
        // 顧客の存在チェック
        if (! DB::table('customers')->where('id', $request->customer_id)->exists()) {
            return response()->json([
                'message' => __('The customer ID (:customer_id) was not found.', ['customer_id' => $request->customer_id]),
                'data' => []
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        // 見積の登録
		DB::beginTransaction();
		try {
			$estimate = new Estimate;
			$estimate->fill($request->all());
			$estimate->setRedundantData(auth()->user());
			$details_input = $request->get('details');
			$details = [];

			foreach ($details_input as $detail) {
				if (!$detail['is_delete']) {
					$details[] = new EstimateDetail($detail);
				}
			}
			$estimate->total_price = $estimate->calc_price($details);
			$estimate->save();
			$estimate->estimate_details()->saveMany($details);
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return back()->withInput();
		}

        $estimate_details = $estimate->estimate_details()->get();
        $data = [
            'estimate' => $estimate,
            'estimate_details' => $estimate_details
        ];

        return response()->json([
            'message' => __('Registered.'),
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estimate = Estimate::find($id);
        // レコードの存在をチェック
        if ($estimate == null) {
            return response()->json([
                'message' => __('Data Not Found.'),
                'data' => []
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        $estimate_details = $estimate->estimate_details()->get();
        $customers = Customer::all();
        $products = Product::all();

        $data = [
            'estimate' => $estimate,
            'estimate_details' => $estimate_details
        ];

        return response()->json([
            'message' => __('Search OK'),
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
	 * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		$estimate = Estimate::find($id);
		$estimate_details = $estimate->estimate_details()->get();
		$customers = Customer::all();
		$products = Product::all();

		// dynamic add detail old inputs
		$dynamic_add_details = [];
		if (old('details')) {
			foreach (old('details') as $key => $detail) {
				if (is_numeric($key) && $key < 0) {
					$dynamic_add_details[] = $detail;
				}
			}
		}

		return view('estimates.edit', compact('estimate', 'estimate_details', 'customers', 'products', 'dynamic_add_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEstimateRequest $request, $id)
    {
        DB::beginTransaction();
		try {
			$estimate = Estimate::find($id);
			$estimate->fill($request->all());
			$estimate->setRedundantData(auth()->user());

			$details_input = $request->get('details');
            $details = [];
			$delete_detail_ids = [];
			foreach ($details_input as $key => $detail_input) {
                if (is_numeric($key) === false ) {
					continue;
				}

                if ($detail_input['is_delete']) {
					$delete_detail_ids[] = $detail_input['id'];
				} else {
					$detail = EstimateDetail::firstOrNew(['id' => $detail_input['id']]);
					$detail->fill($detail_input);
					$details[] = $detail;
				}
			}

			$estimate->total_price = $estimate->calc_price($details);
			$estimate->save();
			$estimate->estimate_details()->saveMany($details);
			EstimateDetail::destroy($delete_detail_ids);

		} catch (Exception $e) {
			DB::rollback();
			return back()->withInput();
		}
		DB::commit();

        // 結果を返す
        $estimate = Estimate::find($id);
        $estimate_details = $estimate->estimate_details()->get();
        $data = [
            'estimate' => $estimate,
            'estimate_details' => $estimate_details
        ];

        return response()->json([
            'message' => __('Updated.'),
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		DB::beginTransaction();
		try {
			$estimate = Estimate::find($id);
            // レコードの存在をチェック
            if ($estimate == null) {
                $message = __('Data Not Found.');
                $data = [];
                $status = 404;
            } else {
                // レコードを削除
                foreach ($estimate->estimate_details as $detail) {
                    $detail->delete();
                }
                $estimate->delete();
                $message = __('Deleted.');
                $data = $estimate;
                $status = 200;
            }
		} catch (Exception $e) {
			DB::rollback();
            return response()->json([
                'message' => 'ng',
                'data' => back()->withInput()
            ], 500, [], JSON_UNESCAPED_UNICODE);
		}
		DB::commit();

        return response()->json([
            'message' => $message,
            'data' => []
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }

	/**
	 * Show the specified estimate report
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function report($id)
	{
		$estimate = Estimate::find($id);

		return view('estimates.report', compact('estimate'));
	}

}
