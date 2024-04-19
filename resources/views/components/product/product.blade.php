@props(['products'])

<div id='productContainerDiv' class="hd:pb-[0.5rem] uhd:pb-0 relative w-full h-full flex items-center bg-white">
    @if ($products->isEmpty())
        <div class="w-full h-full flex items-start justify-start">
            <p class="text-[18px] text-[#717171] ml-[1.8rem] pb-[5rem] pt-[3.6rem]">Er zijn geen producten toegevoegd.</p>
        </div>
    @else
        <ul class="w-full overflow-y-auto overflow-x-hidden product-scrollbar" style="height: 100%;" id="container">
            @foreach ($products as $product)
                <x-product.product-item :product="$product" />
            @endforeach
        </ul>
    @endif
</div>
