@props(['categories', 'checkedCategories' => null])

<div class="flex h-[28.37rem] w-[14.1rem]">
    <div class="w-[14.06rem] h-[5.18rem]">
        <div class="text-[16px] font-[600] relative right-[0.12rem] bottom-[0.18rem]">
            Categorieën
        </div>
        
        <div class="relative right-[0.08rem] underline w-[14rem] h-[0.15rem] bg-[#f8f8f8] mb-2 mt-[0.17rem]">
        </div>
        
        <div id="categoriesContainer">
            <!-- TODO: vragen aan dorus of deze button wel goed is met styling & positioning -->
            <div class="mt-[0.85rem]">
                <input class="sticky rounded-md pl-[1.085rem] pt-[0.05rem] pr-[1rem] text-[#717171] category-search" style="font-size: 14px; border:1px solid #D3D3D3; width:14.06rem; height:2.5rem" type="text" id="categorySearchInput" placeholder="Zoeken">
            </div>
            <ul style="font-family: 'Inter', sans-serif;" id="categoriesList"></ul>
        </div>
    </div>
</div>

<script>
    //Gets the categories data from component
    let categoriesData = [<x-product.categories.product-categories-data :categories="$categories" :checkedCategories="$checkedCategories"/>];
</script>
<script type="text/javascript" src="{{ asset('./assets/js/product/product-categories.js') }}"></script>