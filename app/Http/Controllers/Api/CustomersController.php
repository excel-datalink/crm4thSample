<?php

namespace App\Http\Controllers\Api;

use App;
use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCustomerRequest;
//use App\Http\Requests\CSVUploadRequest;
use SplFileObject;
use Illuminate\Database\QueryException;

class CustomersController extends Controller
{
	public function __construct()
	{
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
		$customers = Customer::searchWordFilter($search_word)->orderBy('name', 'asc')->paginate($rows);
        $customers_count = $customers->count();

        // レコードの存在をチェック
        if ($customers_count == 0) {
            return response()->json([
                'message' => __('Data Not Found.'),
                'data' => $customers
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => __('Displaying :counts item',['counts' => $customers_count]),
                'data' => $customers
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
		$customer = new Customer;
		$customer->fill($request->all());
		$customer->user_id = auth()->id();
		$customer->version = 0;
		$customer->save();

        return response()->json([
            'message' => __('Registered.'),
            'data' => $customer
        ], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$customer = Customer::find($id);

        return response()->json([
            'message' => __('Search OK'),
            'data' => $customer
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);
        $customer->version = $request->input('version');
		$customer->fill($request->all());
        $customer->save();

        return response()->json([
            'message' => __('Updated.'),
            'data' => $customer
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
        try {
            $customer = Customer::find($id);

            // レコードの存在をチェック
            if ($customer == null) {
                $message = __('Data Not Found.');
                $data = [];
                $status = 404;
            } else {
                // レコードを削除
                $customer->delete();
                $message = __('Deleted.');
                $data = $customer;
                $status = 200;
            }

            return response()->json([
                'message' => $message,
                'data' => $data
            ], $status, [], JSON_UNESCAPED_UNICODE);

        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => __("This customer ID (:id) is used in 'Estimates' and could not be deleted.",['id' => $id]),
                    'data' => ''
                ], 422, [], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json([
                    'message' => __('Query Exception Error'),
                    'data' => $e->getMessage()
                ], 422, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
