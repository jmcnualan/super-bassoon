<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'name' => 'Product 1',
                'available_stock' => 1000
            ],
            [
                'id' => 2,
                'name' => 'Product 2',
                'available_stock' => 1000
            ],
        ]);
    }
}
