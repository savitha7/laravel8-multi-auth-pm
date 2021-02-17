<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Exception;
use App\Models\Category;

class CategoryController extends AdminBaseController
{
    /**
     * @var categoryService
     */
    protected $categoryService;

    /**
     * CategoryController Constructor
     *
     * @param CategoryService $categoryService
     *
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result['page_set'] = 'categories';

        return view('admin.modules.category.list', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['page_set'] = 'category_create';

        return view('admin.modules.category.create', $result);
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
            'status',
        ]);

        try {
            $category = $this->categoryService->saveCategoryData($data,$request);
            $result = ['status' => true];
            $result['message'] = trans('notification.created_s', ['obj_name'=>'category']);
            $result['url'] = route('category.index');
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
     * @param  \App\Models\Category  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['page_set'] = 'category_edit';

        $category = $this->categoryService->findCategory($this->decrypt($id));
        $result['name'] = $category->name;
        $result['description'] = $category->description;
        $result['status'] = $category->status;

        return view('admin.modules.category.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $this->decrypt($id);
        $data = $request->only([
            'name',
            'description',
            'status',
        ]);

        try {
            $category = $this->categoryService->updateCategory($data, $id, $request);
            $result = ['status' => true];
            $result['message'] = trans('notification.updated_s', ['obj_name'=>'category']);
            $result['url'] = route('category.index');
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
     * @param  \App\Models\Category  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $id = $this->decrypt($id);
        try {
            $category = $this->categoryService->deleteById($id);
            $result = ['status' => true];
            $result['message'] = trans('notification.deleted_s', ['obj_name'=>'category']);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    public function getCategories(Request $request){

        $result = $this->categoryService->getCategories($request);

        return $this->sendResponse($result, $request);
    }

    public function updateStatus(Request $request){   

        $data = $request->only([
            'status','eid'
        ]);        
        $id = $this->decrypt($data['eid']);
        try {
            $category = $this->categoryService->updateCategoryStatus($id);
            $result = ['status' => true];
            $result['message'] = trans(($category->status?'notification.activated_s':'notification.deactivated_s'), ['obj_name'=>'category']);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        $data['page_set'] = 'category_status';
        return $this->sendResponse($result, $request);
    }
}
