@props(['product'])

<li class="flex w-full h-[4.5rem] py-4 gap-2 items-center justify-center hover:bg-gray-100 transition duration-300">
    <div class="flex items-center relative left-[0.8rem]">
        <div class="flex justify-center items-center w-10 h-[4.5rem] m-0 p-0">
            <input id="checkboxProductItem{{ $product->id }}" class="bulkActionsCheckboxProductItem h-[1.06rem] mx-2 mt-[0.3rem] checkbox-row flex cursor-pointer relative right-[0.1rem]" type="checkbox" name="product_ids[]" value="{{ $product->id }}" >
        </div>

        <a class="rounded-md border-1 flex gap-10 justify-center items-center w-[2.8rem] h-[2.9rem] relative left-0 top-[0.08rem] mr-2"
            style="border: 1px solid #DBDBDB; overflow:visible">
            <img class="w-[2.3rem] h-[2.3rem] pt-[0.01rem]" style="max-width: none;"
                src="{{ $product->primaryPhoto->url }}" />
        </a>
    </div>

    <a class="hoi flex items-center w-full justify-center" href="/products/{{ $product->id }}">
        <div class="flex w-full product-item-title-max-width h-[2.62rem] relative items-center left-[0.75rem]"
            title=" {{ $product->title }}">
            <p class="line-clamp-2 line-clamp-title product-item-title-p-max-width">
                {{ $product->title }}
            </p>
        </div>


    <div class="flex w-full items-center justify-start">
        

        <div class="h-[1] mt-[0.35rem] product-sku-width">
            @php
                if ($product->sku != null) {
                    echo '<p>' . $product->sku . '</p>';
                } else {
                    echo '<p>N.V.T.</p>';
                }
            @endphp
        </div>

        <div class="h-[3rem] product-price-width flex-col relative top-[0.45rem]">
            @if ($product->discount != null)
                <p class="line-through">
                    {{ ' €' . $product->price }}
                </p>
                <p class="font-bold relative bottom-[0.15rem]">
                    {{ '€' . $product->discount }}
                </p>
            @else
                <p class="mt-[0.55rem]">
                    {{ ' €' . $product->price }}
                </p>
            @endif
        </div>
        <div class="product-stock-width h-[1.06rem] relative top-[0.1rem]  ">
            <p>
                {{ $product->stock }}
            </p>
        </div>
        
        <div class="flex">
            <div class="flex w-[3.75rem] relative right-[0.1rem] top-[0.1rem] h-[1.06rem] ">
                {{ $product->sales }}
            </div>
            <div class="flex items-center w-[3.12rem] h-[1.06rem] mt-1">
                @if ($product->sales_channels_exists)
                    <div class="w-1.5 h-1.5 bg-[#3DABD5] rounded-full"></div>
                    <p class="text-[#3DABD5] z-10 flex items-center relative left-1">
                        Online
                    </p>
                @else
                    <div class="w-1.5 h-1.5 bg-[#717171] rounded-full"></div>
                    <p class="text-[#717171] z-10 flex items-center relative left-1">
                        Offline
                    </p>
                @endif
            </div>
        </div>
        <div
            class="h-[1.06rem] text-[14px] mt-[0.18rem] text-[#717171] flex justify-end relative right-[0.75rem] bottom-[0.1rem]">
            {{ $product->updated_at->format('d-m-y H:i') }}
        </div>
    </div>
        

        {{-- @if ($product->concept)
            <strong>Concept</strong>
        @endif --}}
        {{-- allow backorders:
        @if ($product->backorders)
            <strong>true</strong>
        @else
            <strong>false</strong>
        @endif
        keep stock:
        @if ($product->communicate_stock)
            <strong>true</strong>
        @else
            <strong>false</strong>
        @endif --}}
    </a>
</li>
