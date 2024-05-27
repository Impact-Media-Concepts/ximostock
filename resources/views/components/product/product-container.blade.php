{{-- receives the props from parent --}}
@props(['products', 'perPage', 'orderBy', 'discountError', 'salesChannels', 'activeWorkspace'])

<style>
    .bulk-actions-hidden {
        display: none;
    }
</style>

<div class="w-full basic:h-[37.05rem] hd:h-[65.7rem] uhd:h-[71rem]">
    {{-- Every component inside this container component--}}
    <x-product.product-header :products="$products"/>
    <x-product.product-sub-header :orderBy="$orderBy" :products="$products" />
    
    <x-product.popup.product-discount-warning-popup :discountError="$discountError" />
    
    <!-- form for bulk actions -->
    <form class="form-height {{ $products->isEmpty() ? 'hd-form-height' : '' }} basic:h-full hd:h-[49.1rem] uhd:h-5/6 bg-[#484848]" id="bulkActionsForm" action="" method="POST">
        @csrf
        <x-product.popup.sales-channels.product-sales-channels-bulk-popup :salesChannels="$salesChannels" />
        <x-product.product-bulk-actions :discountError="$discountError" :products="$products" :perPage="$perPage"/>
        <x-product.product :activeWorkspace="$activeWorkspace" :products="$products" />
    </form>
    <x-product.product-footer-pagination :perPage="$perPage" :products="$products" />
</div>
