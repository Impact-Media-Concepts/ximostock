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
use App\Models\Supplier;
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

        #region create users

        User::factory()->create([
            'role' => 'admin',
            'work_space_id' => null,
            'email' => 'stage@stage.com'
        ]);
        User::factory()->create([
            'role' => 'manager',
            'work_space_id' => 1,
            'email' => 'manager@1.com'
        ]);
        User::factory()->create([
            'role' => 'supplier',
            'work_space_id' => null,
            'email' => 'supplier@stage.com'
        ]);
        #endregion

        $products = Product::factory(30)->create([
            'work_space_id' => 1,
        ]);


        #region Create categories
        $categories = Category::factory(3)->create([
            'work_space_id' => 1
        ]);
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
        #endregion

        $properties = Property::factory(5)->create();

        #region create Variant products
        $primeProduct = Product::factory(10)->create([
            'work_space_id' => 1,
            'type' => 'main',
            'ean' => null,
            'sku' => null,
        ]);

        //link categories to variants
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

        // Create variants
        foreach($primeProduct as $product){

            $variants = Product::factory(3)->create([
                'work_space_id' => 1,
                'type' => 'variation',
                'parent_product_id' => $product->id,
            ]);
            //link properties to main product
            foreach($properties as $property){
                if(isset($property->optionsDecoded)){
                    $propvalue = $property->optionsDecoded;
                }else{
                    $propvalue = ['value1', 'value2', 'value3'];
                }
                $value = '';
                switch ($property->type) {
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
                    'property_id' => $property->id,
                    'property_value' => json_encode(['value' => $value])
                ]);
            }

            //add categories to prime products
            $randomCategories = Category::all()->random(2); // Adjust the number of random categories as needed
            foreach ($randomCategories as $category) {
                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    'primary' => 1
                ]);
            }

            //link prop to variant
            // Create properties for variants
            $property = Property::factory()->create([
                'work_space_id' => 1,
                'type' => 'singleselect',
            ]);
            foreach ($variants as $variant) {
                ProductProperty::create([
                    'product_id' => $variant->id,
                    'property_id' => $property->id,
                    'property_value' => json_encode(['value' => $faker->word()])
                ]);
            }
        }

        #endregion

        $suppliers = Supplier::factory(15)->create();

        SalesChannel::factory()->create([
            'work_space_id' => 1,
            'name' => 'Ximoshop',
            'url' => 'https://ximoshop.dev-imc.com',
            'api_key' => 'ck_58c3a317b19546d15560b2cc3f19a33ad8e75880',
            'secret' => 'cs_6acaceaf1ad08b001e4532b3c9e3462879f970eb'
        ]);

        // Link properties
        foreach ($properties as $prop) {
            if(isset($prop->OptionsDecoded)){
                $propvalue = $prop->OptionsDecoded;
            }else{
                $propvalue = ['value1', 'value2', 'value3'];
            }
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
