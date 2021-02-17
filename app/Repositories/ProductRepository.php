<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductRepository constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get all product.
     *
     * @return Product $product
     */
    public function getAll()
    {
        return $this->product->get();
    }

    /**
     * Get the product by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->product
            ->where('id', $id)
            ->get();
    }

    /**
     * Save the Product
     *
     * @param $data
     * @return Product
     */
    public function save($data)
    {
        $product = new $this->product;

        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->short_description = $data['short_description'];
        $product->status = $data['status'];
        $product->save();

        if(isset($data['category'])){
            $product->category()->sync([$data['category']]);
        }

        if(!empty($data['saved_images'])){
            $product->images()->createMany($data['saved_images']);
        }
        return $product->fresh();
    }

    /**
     * Update the Product
     *
     * @param $data
     * @return Product
     */
    public function update($data, $id)
    {
        $product = $this->product->find($id);

        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->short_description = $data['short_description'];
        $product->status = $data['status'];

        $product->update();

        if(isset($data['category'])){ 
            $product->category()->sync([$data['category']]);
        }

        if(!empty($data['saved_images'])){
            $product->images()->createMany($data['saved_images']);
        }

        return $product;
    }

    /**
     * Delete the Product
     *
     * @param $data
     * @return Product
     */
    public function delete($id)
    {
        
        $product = $this->product->find($id);
        $product->delete();

        return $product;
    }

    /**
     * Get all Product.
     *
     * @return Product $product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * find the product by id
     *
     * @param $id
     * @return mixed
     */
    public function findProduct($id)
    {
        return $product = $this->product->find($id);
    }

    /**
     * Update the product status
     *
     * @param $data
     * @return Product
     */
    public function updateStatus($id)
    {        
        $product = $this->product->find($id);

        if ($product) {
            if ($product->status == 1) {
                $product->status = 0;
            } else if ($product->status == 0) {
                $product->status = 1;
            }
            $product->update();
        }

        return $product;
    }

    /**
     * Get the product by where condition
     *
     * @param $id
     * @return mixed
     */
    public function getBywhere($condition=[])
    {
        return $this->product
            ->where($condition)
            ->get();
    }
}