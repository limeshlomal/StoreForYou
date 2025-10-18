<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'ELEC',
                'name' => 'Electronics',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'CLOTH',
                'name' => 'Clothing',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'BOOKS',
                'name' => 'Books',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'HOME',
                'name' => 'Home & Garden',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'SPORT',
                'name' => 'Sports & Fitness',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'BEAUTY',
                'name' => 'Beauty & Health',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'AUTO',
                'name' => 'Automotive',
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'code' => 'TOYS',
                'name' => 'Toys & Games',
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
