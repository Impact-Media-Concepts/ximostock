@props(['products'])


<div class="relative flex items-center bg-white">
    <ul class="w-[78.81rem] overflow-y-auto overflow-x-hidden max-h-[43.75rem] product-scrollbar" id="container">
        @foreach ($products as $product)
            <x-product.product-item :product="$product" />
        @endforeach
    </ul>
</div>
