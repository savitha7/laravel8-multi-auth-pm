<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use Exception;
use App\Models\Category;

class ProductController extends AdminBaseController
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

        return view('admin.modules.product.list', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['page_set'] = 'product_create';
        $result['categories'] = $this->categoryService->getBywhere(['status'=>1]);

        return view('admin.modules.product.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'description',
            'short_description',
            'category',
            'featured_image',
            'status',
        ]);

        try {

            $product = $this->productService->saveProductData($data, $request);
            $result = ['status' => true];
            $result['message'] = trans('notification.created_s', ['obj_name'=>'product']);
            $result['url'] = route('product.show', ['product' => $this->encrypt($product->id)]);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['page_set'] = 'category_edit';

        $product = $this->productService->findProduct($this->decrypt($id));
        $result['name'] = $product->name;
        $result['description'] = $product->description;
        $result['short_description'] = $product->short_description;
        $result['status'] = $product->status;
        $result['featured_image'] = $product->featured_image;
        $result['categories'] = $this->categoryService->getBywhere(['status'=>1]);

        foreach ($product->category as $category) {
            $result['category_id'] = $category->id;
        }

        $result['product'] = $product;

        return view('admin.modules.product.edit', $result);
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

        $product = $this->productService->findProduct($this->decrypt($id));
        $result['name'] = $product->name;
        $result['description'] = $product->description;
        $result['short_description'] = $product->short_description;
        $result['status'] = $product->status;
        $result['images'] = $product->featured_image;

        foreach ($product->category as $category) {
            $result['category_name'] = $category->name;
        }

        $result['product'] = $product;

        return view('admin.modules.product.show', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eid)
    {
        $id = $this->decrypt($eid);
        $data = $request->only([
            'name',
            'description',
            'short_description',
            'category',
            'images',
            'status',
        ]);

        try {
            $product = $this->productService->updateProduct($data, $id, $request);
            $result = ['status' => true];
            $result['message'] = trans('notification.updated_s', ['obj_name'=>'product']);
            $result['url'] = route('product.show', ['product' => $eid]);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $id = $this->decrypt($id);
        try {
            $product = $this->productService->deleteById($id);
            $result = ['status' => true];
            $result['message'] = trans('notification.deleted_s', ['obj_name'=>'product']);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    public function getProducts(Request $request){

        $result = $this->productService->getProducts($request);

        return $this->sendResponse($result, $request);
    }

    public function updateStatus(Request $request){   

        $data = $request->only([
            'status','eid'
        ]);        
        $id = $this->decrypt($data['eid']);
        try {
            $product = $this->productService->updateProductStatus($id);
            $result = ['status' => true];
            $result['message'] = trans($product->status?'notification.activated_s':'notification.deactivated_s', ['obj_name'=>'product']);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        $data['page_set'] = 'product_status';
        return $this->sendResponse($result, $request);
    }
    
}
