<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use Exception;

class ProductController extends BaseController
{
    /**
     * @var productService
     */
    protected $productService;

    /**
     * @var categoryService
     */
    protected $categoryService;

    /**
     * ProductController Constructor
     *
     * @param ProductService $productService
     * @param CategoryService $categoryService
     *
     */
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ['status' => true];

        try {
            $result['categories'] = $this->categoryService->getBywhere(['status'=>1]);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        $result['page_set'] = 'products';

        return view('modules.product.list', $result);
    }
   
    /**
     * Show the specified resource.
     *
     * @param  \App\Models\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result['page_set'] = 'product_show';
        $product = $this->productService->getProduct()->where(['status'=>1,'id'=>$this->decrypt($id)])->orderBy('products.id', 'desc')->first();

        if(!$product){
            abort(405);
        }

        $result['name'] = $product->name;
        $result['description'] = $product->description;
        $result['short_description'] = $product->short_description;
        $result['status'] = $product->status;
        $result['images'] = $product->featured_image;

        foreach ($product->category as $category) {
            $result['category_name'] = $category->name;
        }

        $result['product'] = $product;

        return view('modules.product.show', $result);
    }


    public function getProducts(Request $request){

        $result = $this->productService->getUserProducts($request);

        return $this->sendResponse($result, $request);
    }
    
}
