<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use InvalidArgumentException;
use Illuminate\Validation\Rule;
use App\Models\ProductCategory;

class CategoryService extends BaseService
{
    /**
     * @var $categoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }    

    /**
     * Get all category.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * Get the category by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->categoryRepository->getById($id);
    }

    /**
     * Get the categories by id.
     *
     * @param $id
     * @return String
     */
    public function getBywhere($condition)
    {
        return $this->categoryRepository->getBywhere($condition);
    }

    /**
     * Get the category by id.
     *
     * @param $id
     * @return String
     */
    public function findCategory($id)
    {
        return $this->categoryRepository->findCategory($id);
    }

    /**
     * Validate the category data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function saveCategoryData($data,$request)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }
        
        $result = $this->categoryRepository->save($data);

        return $result;
    }

    /**
     * Update the category data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateCategory($data, $id, $request)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {

            $category = $this->categoryRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update the category.');
        }

        DB::commit();

        return $category;

    }    

    /**
     * Delete the category by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            if($getCategory = $this->findCategory($id)){                
                $getProduct = ProductCategory::where('category_id', $getCategory->id)->pluck('product_id')->toArray();

                if(!empty($getProduct)){
                    $category = $this->findCategory('1'); //default category
                    $category->products()->syncWithoutDetaching($getProduct);
                }

                $category = $this->categoryRepository->delete($id);                              
            }            

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete the category.');
        }

        DB::commit();

        return $category;

    }

    public function getCategories($request){
        $data = array();
        $count_total = $count_filter = false;
        $search_for = false;
        if ($request->filled('search.value')) {
            $search_for = $request->input('search.value');
        }

        $buildQuery = $this->categoryRepository->getCategory()->orderBy('category.id', 'desc');

        if ($search_for) {
            $buildQuery->where(function ($query) use ($search_for) {
                $query->where('category.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('category.description', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('category.status', 'LIKE', '%' . $search_for . '%');
                $query->orWhere(DB::raw("DATE_FORMAT(category.created_at, '%b %d, %Y')"), 'LIKE', '%' . $search_for . '%');
            });
        }

        $start = 0;
        $length = 10;
        if ($request->filled('start')) {
            $start = $request->start;
        }
        if ($request->filled('length')) {
            $length = $request->length;
        }

        $query_total = $query = $buildQuery->get();
        if ($request->length != -1) {
            $query = $buildQuery->limit($length)->offset($start)->get();
        }
        $categories = $query;

        if ($categories->count() > 0) {

            foreach ($categories as $key => $category) {
                $action = '';
                $eid = $this->encrypt($category->id);                
                /* Toggle Button */
                $status = '<label class="switch"><input type="checkbox" class="status_checkbox" name="'.$eid.'" data-id="'.$eid.'" '.(($category->status)?"checked":"").'><span onclick="updateStatus(this)" class="slider round" data-id="'.$eid.'"></span></label>';
                if($category->id != 1){
                    $action .= '<a href="' . route('category.edit', ['category' => $eid]) . '" type="button" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-edit"></i></a>';
                    $action .= ' <button type="button" class="tw-modal-open text-red-500 hover:hover:text-red-700" title="Delete" data-toggle="modal" data-target="#delete_tw_modal" data-action="' .route('category.destroy', ['category' => $eid]) . '" data-msg="Are you sure you want to delete the category <strong>' . $category->name . '</strong>?"><i class="fa fa-fw fa-trash"></i></button>';
                }
                
                $data[$key][0] = $key + 1;
                $data[$key][1] = $category->name;
                $data[$key][2] = $category->description;
                $data[$key][3] = $status;
                $data[$key][4] = date('M d, Y', strtotime($category->created_at));
                $data[$key][5] = $action;                
            }
        }

        $count_total = $query_total->count();
        $count_filter = $query->count();

        $dt_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($count_total),
            "recordsFiltered" => intval($count_filter),
            "data" => $data,
        );

        return $dt_data;
    }
    
    /**
     * Update the category status
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateCategoryStatus($id)
    { 
        DB::beginTransaction();

        try {
            $category = $this->categoryRepository->updateStatus($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update the category.');
        }

        DB::commit();

        return $category;

    }

}