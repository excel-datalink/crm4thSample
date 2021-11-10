<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App;
use App\Product;
use App\Http\Requests\StoreProductRequest;
use App\Facades\CSV;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ProductsController extends Controller
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
		$search_max_standard_price = $request->input('search_max_standard_price');
		$search_min_standard_price = $request->input('search_min_standard_price');

		$products = Product::nameFilter($search_word)
					->maxStandardPriceFilter($search_max_standard_price)
					->minStandardPriceFilter($search_min_standard_price)
					->orderBy('name', 'asc');

		switch ($request->submit_btn) {
//			case 'CSV':
//				$products_array = $products->get(['name', 'standard_price'])->toArray();
//				$headers = ['商品名', '標準単価'];
//
//				return CSV::download($products_array, $headers, 'products_list.csv');
//				break;
			default:
				$products = $products->paginate($rows);
                $products_count = $products->count();

                // レコードの存在をチェック
                if ($products_count == 0) {
                    return response()->json([
                        'message' => __('Data Not Found.'),
                        'data' => $products
                    ], 404, [], JSON_UNESCAPED_UNICODE);
                } else {
                    return response()->json([
                        'message' => __('Displaying :counts item',['counts' => $products_count]),
                        'data' => $products
                    ], 200, [], JSON_UNESCAPED_UNICODE);
                }
				break;
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$product = new Product;
		return view('products.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
		$product = new Product;
		$product->fill($request->all());
		$product->user_id = auth()->id();
		$product->save();

        return response()->json([
            'message' => __('Registered.'),
            'data' => $product
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
		$product = Product::find($id);

        return response()->json([
            'message' => __('Search OK'),
            'data' => $product
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$product = Product::find($id);
		
		return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {
		$product = Product::find($id);
		$product->fill($request->all());
		$product->save();

        return response()->json([
            'message' => __('Updated.'),
            'data' => $product
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
            $product = Product::find($id);

            // レコードの存在をチェック
            if ($product == null) {
                $message = __('Data Not Found.');
                $data = [];
                $status = 404;
            } else {
                // レコードを削除
                $product->delete();
                $message = __('Deleted.');
                $data = $product;
                $status = 200;
            }

            return response()->json([
                'message' => $message,
                'data' => $data
            ], $status, [], JSON_UNESCAPED_UNICODE);

        } catch (QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => __("This product ID (:id) is used in 'Estimate' and cannot be deleted.", ['id' => $id]), // 'この商品ID（' .$id ."）は「見積」で使用されているため、削除できません。\n"
                    'data' => []
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
