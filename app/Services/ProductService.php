<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use InvalidArgumentException;
use Illuminate\Validation\Rule;
use App\Exceptions\CustomException;
use App\Models\ProductImages;
use File;

class ProductService extends BaseService
{
    /**
     * @var $productRepository
     */
    protected $productRepository;

    protected const UPLOAD_PRODUCTS_IMG = 'products';

    /**
     * Service constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }    

    /**
     * Get all product.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    /**
     * Get the product by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->productRepository->getById($id);
    }

    /**
     * Get the product by id.
     *
     * @param $id
     * @return String
     */
    public function getBywhere($condition)
    {
        return $this->productRepository->getBywhere($condition);
    }

    /**
     * Get the Product.
     *
     * @return Product $product
     */
    public function getProduct()
    {
        return $this->productRepository->getProduct();
    }

    /**
     * Get the product by id.
     *
     * @param $id
     * @return String
     */
    public function findProduct($id)
    {
        return $this->productRepository->findProduct($id);
    }

    /**
     * Validate the product data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function saveProductData($data,$request=null)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'short_description' => 'nullable|max:1000',
            'images.*' => 'nullable|image:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $data['saved_images'] = [];
        if($request->hasFile('images'))
        {   
            $images =[];
            foreach($request->file('images') as $key => $file){
                if($file->isValid()){
                    $imageName = $key.time().'.'.$file->extension();
                    $image_uploaded_path = $file->store(self::UPLOAD_PRODUCTS_IMG, 'public', $imageName);
          
                    $images[$key]['image_name'] = basename($image_uploaded_path);
                }
            }
            $data['saved_images'] = $images;
        }

        $result = $this->productRepository->save($data);
        
        return $result;
    }

    /**
     * Update the product data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateProduct($data, $id, $request=null)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'short_description' => 'nullable|max:1000',
            'images.*' => 'nullable|image:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
            'category' => 'required',
        ]);   

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $getProduct = $this->findProduct($id);

            $data['saved_images'] = [];
            if($request->hasFile('images'))
            {   
                $images =[];
                foreach($request->file('images') as $key => $file){
                    if($file->isValid()){
                        $imageName = $key.time().'.'.$file->extension();
                        $image_uploaded_path = $file->store(self::UPLOAD_PRODUCTS_IMG, 'public', $imageName);
              
                        $images[$key]['image_name'] = basename($image_uploaded_path);
                    }
                }
                $data['saved_images'] = $images;
            }

            $product = $this->productRepository->update($data, $id);

            //remove the images
            if($product && $request->remove_img && !empty($request->remove_img)){              
                foreach ($request->remove_img as $key => $img) {
                    $productImgs = ProductImages::find($img);
                    $productImgs->delete();
                    $filename = storage_path('app/public/products/'.$productImgs->image_name);
                    if(File::exists($filename)) {
                        File::delete($filename);
                    }
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update the product. '. $e->getMessage());
        }

        DB::commit();

        return $product;

    }    

    /**
     * Delete the product by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete the product.');
        }

        DB::commit();

        return $product;

    }

    public function getProducts($request){
        $data = array();
        $count_total = $count_filter = false;
        $search_for = false;
        if ($request->filled('search.value')) {
            $search_for = $request->input('search.value');
        }

        $buildQuery = $this->productRepository->getProduct()->select(DB::raw("products.name as pname, category.name cname, products.description as pdescription, products.id,products.created_at,products.status"))
            ->join('product_category', 'products.id', '=', 'product_category.product_id')
            ->join('category', 'product_category.category_id', '=', 'category.id')
            ->orderBy('products.id', 'desc');

        if ($request->filled('category')) {
            $category_id = $this->decrypt($request->input('category'));
            $buildQuery = $buildQuery->where(['category.id'=>$category_id]);
        }

        if ($search_for) {
            $buildQuery->where(function ($query) use ($search_for) {
                $query->where('products.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('category.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('products.description', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('products.short_description', 'LIKE', '%' . $search_for . '%');
                $query->orWhere(DB::raw("DATE_FORMAT(products.created_at, '%b %d, %Y')"), 'LIKE', '%' . $search_for . '%');
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
        $products = $query;

        if ($products->count() > 0) {

            foreach ($products as $key => $product) {
                $action = '';
                $eid = $this->encrypt($product->id);
                /* Toggle Button */
                $status = '<label class="switch"><input type="checkbox" class="status_checkbox" name="'.$eid.'" data-id="'.$eid.'" '.(($product->status)?"checked":"").'><span onclick="updateStatus(this)" class="slider round" data-id="'.$eid.'"></span></label>';
                $action .= ' <a href="' . route('product.edit', ['product' => $eid]) . '" type="button" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-edit"></i></a>';
                $action .= ' <a href="' . route('product.show', ['product' => $eid]) . '" type="button" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-eye"></i></a>';
                $action .= ' <button type="button" class="tw-modal-open text-red-500 hover:hover:text-red-700" title="Delete" data-toggle="modal" data-target="#delete_tw_modal" data-action="' .route('product.destroy', ['product' => $eid]) . '" data-msg="Are you sure you want to delete the product <strong>' . $product->pname . '</strong>?"><i class="fa fa-fw fa-trash"></i></button>';
                
                $data[$key][0] = $key+1;
                $data[$key][1] = $product->pname;
                $data[$key][2] = $product->pdescription;
                $data[$key][3] = $product->cname;
                $data[$key][4] = $status;
                $data[$key][5] = date('M d, Y', strtotime($product->created_at));
                $data[$key][6] = $action;              
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
     * Update the product status
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateProductStatus($id)
    { 
        DB::beginTransaction();

        try {
            $product = $this->productRepository->updateStatus($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update the product.');
        }

        DB::commit();

        return $product;

    }

    public function getUserProducts($request){
        $data = array();
        $count_total = $count_filter = false;
        $search_for = false;
        if ($request->filled('search.value')) {
            $search_for = $request->input('search.value');
        }
        
        $buildQuery = $this->productRepository->getProduct()->select(DB::raw("products.name as pname, category.name cname, products.description as pdescription, products.id"))
            ->join('product_category', 'products.id', '=', 'product_category.product_id')
            ->join('category', 'product_category.category_id', '=', 'category.id')
            ->where('products.status',1)->orderBy('products.id', 'desc');

        if ($request->filled('category')) {
            $category_id = $this->decrypt($request->input('category'));
            $buildQuery = $buildQuery->where(['category.status'=>1,'category.id'=>$category_id]);
        }

        if ($search_for) {
            $buildQuery->where(function ($query) use ($search_for) {
                $query->where('products.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('category.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('products.description', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('products.short_description', 'LIKE', '%' . $search_for . '%');
                $query->orWhere(DB::raw("DATE_FORMAT(products.created_at, '%b %d, %Y')"), 'LIKE', '%' . $search_for . '%');
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
        $products = $query;

        if ($products->count() > 0) {

            foreach ($products as $key => $product) {
                $action = $category_name = '';
                $eid = $this->encrypt($product->id);
                /* Toggle Button */
                $action .= ' <a href="' . route('user.product.show', ['product' => $eid]) . '" type="button" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-eye"></i></a>';
                
                $data[$key][0] = $key+1;
                $data[$key][1] = $product->pname;
                $data[$key][2] = $product->pdescription;
                $data[$key][3] = $product->cname;
                $data[$key][4] = $action;              
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

}