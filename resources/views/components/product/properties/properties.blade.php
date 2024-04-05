@props(['properties' => null, 'selectedProperties' => null])

<style>
    .rotate-arrow {
    transform: rotate(180deg);
}
</style>

<div class="flex grid justify-center h-[28.37rem]">
    <div class="h-[5.18rem]">
        <div class="text-[16px] font-[600] relative right-[0.12rem]">
            Eigenschappen
        </div>

        <div class="relative right-[0.08rem] underline w-[14rem] h-[0.15rem] bg-[#f8f8f8] mb-1 mt-[0.17rem]">
        </div>

        <input class="sticky property-search-input w-[14.06rem] h-[2.5rem] rounded-md mt-[0.55rem]" style="border: 1px solid #D3D3D3;" type="text" id="propertySearchInput" placeholder="Zoeken" />
        <ul  class="mt-[0.85rem]" id="propertyList"></ul>
    </div>
</div>

<x-product.properties.properties-data :properties="$properties" :selectedProperties="$selectedProperties"/>

<script type="text/javascript" src="{{ asset('./assets/js/product/properties.js') }}"></script>
