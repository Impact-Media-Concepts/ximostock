<?php

namespace Database\Seeders;

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
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $workSpaces = WorkSpace::factory(3)->create();

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
        $categories = Category::factory(3)->create();

        $products = Product::factory(50)->create([
            'work_space_id' => 1,
            'price' => $faker->numberBetween(1, 1000),
            'discount' => $faker->numberBetween(0, 50),
        ]);
        $products_second = Product::factory(50)->create([
            'work_space_id' => 2,
            'price' => $faker->numberBetween(1, 1000),
            'discount' => $faker->numberBetween(0, 50),
        ]);

        $primeProducts = Product::factory(25)->create([
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

        // Link properties
        foreach ($properties as $prop) {
            Log::info('Property');
            Log::info($prop);
            $propvalue = $prop->options;
            foreach ($products as $product) {
                $value = '';
                switch ($prop->type) {
                    case 'multiselect':
                        $value = [
                            $faker->randomElement($propvalue), 
                            $faker->randomElement($propvalue)
                        ];
                        break;
                    case 'singleselect':
                        $value = $faker->randomElement($propvalue);
                        break;
                    case 'number':
                        $value = $faker->numberBetween(0, 16);
                        break;
                    case 'text':
                        $value = $faker->word();
                        break;
                    case 'bool':
                        $value = $faker->boolean();
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

        // Create subcategories
        foreach ($categories as $category) {
            $subcategories = Category::factory(2)->create([
                'parent_category_id' => $category,
                'work_space_id' => 1
            ]);
            foreach ($subcategories as $subcategory) {
                $subsubcategories = Category::factory(2)->create([
                    'parent_category_id' => $subcategory,
                    'work_space_id' => 1
                ]);
                foreach ($subsubcategories as $subsubcategory) {
                    Category::factory(2)->create([
                        'parent_category_id' => $subsubcategory,
                        'work_space_id' => 1
                    ]);
                }
            }
        }

        // Link products and categories randomly
        foreach ($products as $product) {
            $randomCategories = Category::all()->random(2); // Adjust the number of random categories as needed
            foreach ($randomCategories as $category) {
                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    'primary' => 1
                ]);
            }
        }

        // Link products and categories randomly for products_second
        foreach ($products_second as $product) {
            $randomCategories = Category::all()->random(1); // Adjust the number of random categories as needed
            foreach ($randomCategories as $category) {
                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    'primary' => 1
                ]);
            }
        }

        // Link location zones
        $locations = InventoryLocation::factory(4)->create();
        $zones = [];
        foreach ($locations as $location) {
            array_push($zones, LocationZone::factory(3)->create([
                'inventory_location_id' => $location->id
            ]));
        }

        // Link stock and photos
        foreach ($products as $product) {
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
    }
}
