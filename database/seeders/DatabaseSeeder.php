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
use App\Models\ProductProperty;
use App\Models\ProductSalesChannel;
use App\Models\Property;
use App\Models\Sale;
use App\Models\SalesChannel;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $workSpaces = WorkSpace::factory(5)->create();


        User::factory()->create([
            'role' => 'admin',
            'work_space_id' => null,
            'email' => 'stage@stage.com'
        ]);
        User::factory()->create([
            'role' => 'manager',
            'work_space_id' => 2,
            'email' => 'manager@1.com'
        ]);
        User::factory()->create([
            'role' => 'manager',
            'work_space_id' => 2,
            'email' => 'manager@2.com'
        ]);
        User::factory()->create([
            'role' => 'supplier',
            'work_space_id' => null
        ]);
        User::factory()->create([
            'role' => 'admin',
            'work_space_id' => null
        ]);
        User::factory()->create([
            'role' => 'supplier',
            'work_space_id' => null,
            'email' => 'supplier@stage.com'
        ]);
        $categories = Category::factory(5)->create();

        $products = Product::factory(100)->create([
            'work_space_id' => 2,
        ]);

        $primeProducts = Product::factory(50)->create([
            'work_space_id' => 2,
            'sku' => null,
            'ean' => null
        ]);

        $products = $products->concat($primeProducts);


        $properties = Property::factory(5)->create();

        SalesChannel::factory()->create([
            'work_space_id' => 2,
            'name' => 'XimostockCommerce',
            'url' => 'https://test.com',
            'api_key' => 'ck_cc7e1e85dbe0f56504134ee5caa3a114351e0012',
            'secret' => 'cs_9d52cf66d9fc3d26cf693b912f2d41c1db005020'
        ]);
        SalesChannel::factory()->create([
            'work_space_id' => 2,
            'name' => 'ximoshop',
            'url' => 'https://ximoshop.dev-imc.com',
            'api_key' => 'ck_6307bb7387ba14f2b2440a3d6b4add2be3c28c69',
            'secret' => 'cs_c6581c4eb86e71728eeee93224214b0d5594c137'
        ]);
        $saleschannels = SalesChannel::factory(4)->create([
            'work_space_id' => 2,
        ]);
        SalesChannel::factory(3)->create([
            'work_space_id' => 3,
        ]);

        //link properties
        foreach ($properties as $prop) {
            $propvalue = json_decode($prop->values);
            foreach ($products as $product) {
                $value = '';
                switch ($propvalue->type) {
                    case 'multiselect':
                        $value = [fake()->randomElement($propvalue->options), fake()->randomElement($propvalue->options)];
                        break;
                    case 'singleselect':
                        $value = fake()->randomElement($propvalue->options);
                        break;
                    case 'number':
                        $value = fake()->numberBetween(0, 16);
                        break;
                    case 'text':
                        $value = fake()->word();
                        break;
                    case 'bool':
                        $value = fake()->boolean();
                        break;
                }
                ProductProperty::create([
                    'product_id' => $product->id,
                    'property_id' => $prop->id,
                    'property_value' => json_encode(['value' => $value])
                ]);
            }
        }

        foreach ($primeProducts as $product) {
            Product::factory(3)->create([
                'parent_product_id' => $product->id,
                'title' => null,
                'short_description' => null,
                'long_description' => null,
                'price' => null
            ]);
        }

        //create subcategories
        foreach ($categories as $category) {
            $subcategories = Category::factory(5)->create([
                'parent_category_id' => $category
            ]);
            foreach($subcategories as $category){
                $subcategories = Category::factory(5)->create([
                    'parent_category_id' => $category
                ]);
                foreach($subcategories as $category){
                    $subcategories = Category::factory(5)->create([
                        'parent_category_id' => $category
                    ]);
                    foreach($subcategories as $category){
                        $subcategories = Category::factory(5)->create([
                            'parent_category_id' => $category
                        ]);
                    }
                }
            }
        }

        //link categories
        foreach ($products as $product) {
            CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $categories[0]->id,
                'primary' => 1
            ]);
        }

        //link location zones
        $locations = InventoryLocation::factory(4)->create();
        $zones = [];
        foreach ($locations as $location) {
            (array_push($zones, LocationZone::factory(3)->create([
                'inventory_location_id' => $location->id
            ])));
        }

        //link stock and photos
        foreach ($products as $product) {
            // for ($x = 1; $x <= 8; $x++) {
            //     Inventory::factory()->create([
            //         'product_id' => $product->id,
            //         'location_zone_id' => $x
            //     ]);
            // }
            PhotoProduct::create([
                'photo_id' => Photo::factory()->create()->id,
                'product_id' => $product->id,
                'primary' => true
            ]);
            for ($x = 1; $x <= 4; $x++) {
                PhotoProduct::create([
                    'photo_id' => Photo::factory()->create()->id,
                    'product_id' => $product->id,
                    'primary' => false
                ]);
            }
        }

        // // link salesChannels
        // $productSalesChannels = [];
        // for ($x = 1; $x <= 500; $x++) {
        //     for ($y = 1; $y <= 3; $y++)
        //         array_push($productSalesChannels, ProductSalesChannel::create([
        //             'product_id' => $x,
        //             'sales_channel_id' => $y
        //         ]));
        // }

    }
}
