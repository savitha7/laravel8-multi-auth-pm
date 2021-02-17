<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * @var Category
     */
    protected $category;

    /**
     * CategoryRepository constructor.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get all category.
     *
     * @return Category $category
     */
    public function getAll()
    {
        return $this->category->get();
    }

    /**
     * Get the category by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->category
            ->where('id', $id)
            ->get();
    }

    /**
     * Save the Category
     *
     * @param $data
     * @return Category
     */
    public function save($data)
    {
        $category = new $this->category;

        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->status = $data['status'];
        $category->save();

        return $category->fresh();
    }

    /**
     * Update the Category
     *
     * @param $data
     * @return Category
     */
    public function update($data, $id)
    {
        $category = $this->category->find($id);

        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->status = $data['status'];
        $category->update();

        return $category;
    }

    /**
     * Delete the Category
     *
     * @param $data
     * @return Category
     */
    public function delete($id)
    {
        
        $category = $this->category->find($id);
        $category->delete();

        return $category;
    }

    /**
     * Get all category.
     *
     * @return Category $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * find the category by id
     *
     * @param $id
     * @return mixed
     */
    public function findCategory($id)
    {
        return $category = $this->category->find($id);
    }

    /**
     * Update the Category status
     *
     * @param $data
     * @return Category
     */
    public function updateStatus($id)
    {        
        $category = $this->category->find($id);

        if ($category) {
            if ($category->status == 1) {
                $category->status = 0;
            } else if ($category->status == 0) {
                $category->status = 1;
            }
            $category->update();
        }

        return $category;
    }

    /**
     * Get the category by where condition
     *
     * @param $id
     * @return mixed
     */
    public function getBywhere($condition=[])
    {
        return $this->category
            ->where($condition)
            ->get();
    }
}