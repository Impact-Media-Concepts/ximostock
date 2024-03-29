{{-- receives the props from parent --}}
@props(['products', 'perPage'])

<style>
    .bulk-actions-hidden {
        display: none;
    }
</style>

<div class="w-full h-full">
    {{-- Every component inside this container component--}}
    <x-product.product-header :products="$products"/>
    <x-product.product-sub-header :products="$products" />

    <!-- form for bulk actions -->
    <form class="h-5/6" id="bulkActionsForm" action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf
        <x-product.product-bulk-actions :products="$products" :perPage="$perPage"/>
        <x-product.product :products="$products" />
    </form>
    <x-product.product-footer-pagination :perPage="$perPage" :products="$products" />
</div>
