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
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory(5)->create();

        $products = Product::factory(5000)->create();
        $primeProducts = Product::factory(1000)->create([
            'sku' => null,
            'ean' => null
        ]);

        $products = $products->concat($primeProducts);


        $properties = Property::factory(20)->create();

        $saleschannels = SalesChannel::factory(5)->create();

        //link properties
        foreach ($properties as $prop) {
            $propvalue = json_decode($prop->values);
            foreach ($products as $product) {
                $value = '';
                switch ($propvalue->type) {
                    case 'multiselect':
                        $value = fake()->randomElement($propvalue->options);
                        break;
                    case 'singelselect':
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
            for ($x = 1; $x <= 8; $x++) {
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
            for ($x = 1; $x <= 4; $x++) {
                PhotoProduct::create([
                    'photo_id' => Photo::factory()->create()->id,
                    'product_id' => $product->id,
                    'primary' => false
                ]);
            }
        }

        //link salesChannels
        $productSalesChannels = [];
        for ($x = 1; $x <= 5; $x++) {
            for ($y = 1; $y <= 3; $y++)
                array_push($productSalesChannels, ProductSalesChannel::create([
                    'product_id' => $x,
                    'sales_channel_id' => $y
                ]));
        }

        foreach ($productSalesChannels as $sale) {
            Sale::create([
                'product_sales_channel_id' => $sale->id,
                'price' => fake()->numberBetween(10, 30),
                'stock' => fake()->numberBetween(0, 20)
            ]);
        }
    }
}
