{{-- receives the props from parent --}}
@props(['products', 'perPage', 'orderBy', 'discountError'])

<style>
    .bulk-actions-hidden {
        display: none;
    }
</style>

<div class="w-full h-full">
    {{-- Every component inside this container component--}}
    <x-product.product-header :products="$products"/>
    <x-product.product-sub-header :orderBy="$orderBy" :products="$products" />

    <x-product.popup.product-discount-warning-popup :discountError="$discountError" />

    <!-- form for bulk actions -->
    <form class="form-height {{ $products->isEmpty() ? 'hd-form-height' : '' }} uhd:h-5/6" id="bulkActionsForm" action="" method="POST">
        @csrf
        <x-product.product-bulk-actions :discountError="$discountError" :products="$products" :perPage="$perPage"/>
        <x-product.product :products="$products" />
    </form>
    <x-product.product-footer-pagination :perPage="$perPage" :products="$products" />
</div>
