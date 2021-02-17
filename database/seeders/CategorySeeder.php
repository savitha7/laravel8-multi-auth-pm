<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
	     * Add Category
	     *
		 */
        if (Category::where(['name'=>'Miscellaneous'])->first() === null) {
	        $category = Category::create([
	            'name' => 'Miscellaneous',
	            'description' => 'Default category.',
        	]);
	    }				
    }
}
