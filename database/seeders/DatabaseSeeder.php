<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\LocationZone;
use App\Models\Photo;
use App\Models\PhotoProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Foreach_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory(5)->create();

        $products = Product::factory(10)->create();

        foreach($categories as $category){
            Category::factory(5)->create([
                'parent_category_id'=>$category
            ]);
        }

        foreach($products as $product){
            CategoryProduct::create([
                'product_id'=>$product->id,
                'category_id'=>$categories[0]->id,
                'primary'=>1
            ]);
        }

        $locations = InventoryLocation::factory(4)->create();
        $zones = [];
        foreach($locations as $location){
            (array_push($zones ,LocationZone::factory(3)->create([
                'inventory_location_id' => $location->id
            ]))) ;
        }

        foreach($products as $product){
            for($x = 1; $x <= 8; $x++){
                Inventory::factory()->create([
                    'product_id' => $product->id,
                    'location_zone_id' => $x
                ]);
            }
            PhotoProduct::create([
                'photo_id' => Photo::factory()->create()->id,
                'product_id' => $product->id,
                'primary' => true
            ]);
        }
    }
}
