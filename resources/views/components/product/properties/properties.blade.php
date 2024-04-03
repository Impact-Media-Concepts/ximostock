@props(['properties' => null])
<style>
    .rotate-arrow {
    transform: rotate(180deg);
}
</style>
<div class="flex grid justify-center h-[28.37rem]">
    <div>
        <input class="property-search-input w-[14.06rem] h-[2.5rem] rounded-md" style="border: 1px solid #D3D3D3;" type="text" id="propertySearchInput" placeholder="Zoeken" />
        <ul class="mt-[0.8rem]" id="propertyList"></ul>
    </div>
</div>

<x-product.properties.properties-data :properties="$properties"/>
<script type="text/javascript" src="{{ asset('./assets/js/product/properties.js') }}"></script>
