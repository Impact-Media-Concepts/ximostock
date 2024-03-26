@props(['products'])


<div class="relative w-full h-full flex items-center bg-white">
    <ul class="w-full overflow-y-auto overflow-x-hidden product-scrollbar"  style="height: 100%;" id="container">
        @foreach ($products as $product)
            <x-product.product-item :product="$product" />
        @endforeach
    </ul>
</div>
