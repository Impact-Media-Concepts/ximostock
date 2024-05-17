@props(['categories', 'checkedCategories' => null])

<?php
    $app_url = env('VITE_APP_URL');
?>

<div id='categoriesContainer'>
    <div class='w-full flex justify-center items-center'>
        <input id='createProductCategoriesSearch' class='sticky property-search-input basic:w-[28.5rem] hd:w-[43.43rem] uhd:w-[61.43rem] h-[2.5rem] rounded-md mt-[1.5rem] rounded-b-lg' style='border: 1px solid #D3D3D3;' type='text' placeholder='Zoeken' />
    </div>
    <ul id='categoriesList' class='basic:max-h-[25.4rem] basic:h-[25.4rem] hd:max-h-[37.5rem] hd:h-[37.5rem] uhd:max-h-[44rem] uhd:h-[44rem] overflow-y-auto'></ul>
</div>
<script>
    let categoriesData = [
        <x-product.create.categories.category-data :categories='$categories' :checkedCategories='$checkedCategories'/>
    ];
    const create_prod_categories_app_url = {!! json_encode($app_url) !!};
</script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-product-categories.js') }}"></script>
