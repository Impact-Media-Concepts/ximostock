@props(['products'])

<div class="my-[7rem] mx-[2rem]">
    <x-product.product-header />
    <x-product.product-sub-header />
    <x-product.product-bulk-actions />
    <x-product :products="$products" />
    <x-product.product-footer-pagination />
</div>
