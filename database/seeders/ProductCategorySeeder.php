<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_EN');

        for ($i=0; $i < 20; $i++) { 
            ProductCategory::insert([
                'name' => $faker->,
            ]);
        }
    }
}
