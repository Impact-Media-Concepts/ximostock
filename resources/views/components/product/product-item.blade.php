@props(['product'])

<li class="flex w-full h-[4.5rem] py-4 gap-2 items-center justify-center hover:bg-gray-100 transition duration-300">
    <div class="flex items-center relative left-[0.8rem]">
        <div class="flex justify-center items-center w-10 h-[4.5rem] m-0 p-0">
            <input id="checkboxProductItem{{ $product->id }}" class="bulkActionsCheckboxProductItem h-[1.06rem] mx-2 mt-[0.3rem] checkbox-row flex cursor-pointer relative right-[0.1rem]" type="checkbox" name="product_ids[]" value="{{ $product->id }}" >
        </div>
        <a class="rounded-md border-1 flex gap-10 justify-center items-center w-[2.8rem] h-[2.9rem] relative left-0 top-[0.08rem] mr-2 uhd:mr-[1.5rem]"
            style="border: 1px solid #DBDBDB; overflow:visible">
            <img class="select-none w-[2.3rem] h-[2.3rem] pt-[0.01rem]" style="max-width: none;"
                src="{{ $product->primaryPhoto->url }}" alt="{{ $product->title }}" />
        </a>
    </div>
    <a class="hoi flex items-center w-full justify-center hd:gap-1 uhd:gap-[1.1rem]" href="/products/{{ $product->id }}">
        <div class="flex w-full hd:max-w-[21.3rem] uhd:max-w-[27.9rem] shd:max-w-[42.5rem] w-full h-[2.62rem] relative items-center left-[0.75rem]"
            title="{{ $product->title }}">
            <p class="line-clamp-2 hd:max-w-[20rem] uhd:max-w-[27.9rem] shd:max-w-[42.5rem]">
                {{ $product->title }}
            </p>
        </div>
        <div class="flex w-full items-center justify-start">
            <div class="mt-[0.35rem] hd:w-[10.8rem] uhd:w-[16.3rem] shd:w-[22.1rem]">
                @php
                    if ($product->sku != null) {
                        echo '<p>' . $product->sku . '</p>';
                    } else {
                        echo '<p>N.V.T.</p>';
                    }
                @endphp
            </div>
            <div class="h-[3rem] hd:w-[8rem] uhd:w-[16.1rem] shd:w-[22.7rem] flex-col relative top-[0.45rem]">
                @if ($product->discount != null)
                    <p class="line-through">
                        {{ ' €' . $product->price }}
                    </p>
                    <p class="font-bold relative bottom-[0.15rem]">
                        {{ '€' . $product->discount }}
                    </p>
                @else
                    <p class="mt-[0.6rem]">
                        {{ ' €' . $product->price }}
                    </p>
                @endif
            </div>
            <div class="hd:w-[9rem] uhd:w-[14.4rem] shd:w-[27.2rem] h-[1.06rem] relative top-[0.1rem]  ">
                <p>
                    {{ $product->stock }}
                </p>
            </div>

            <div class="flex hd:w-[7.5rem] uhd:w-[13.35rem] shd:w-[26.7rem] relative right-[0.1rem] top-[0.1rem] h-[1.06rem] ">
                <p>
                    {{ $product->sales }}
                </p>
            
            </div>
            <div class="flex items-center hd:w-[8.2rem] uhd:w-[14.9rem] shd:w-[27.8rem] h-[1.06rem] mt-1">
                @if ($product->sales_channels_exists)
                    <div class="mt-[0.15rem] w-1.5 h-1.5 bg-[#3DABD5] rounded-full"></div>
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
            <div
                class="h-[1.06rem] text-[14px] mt-[0.18rem] text-[#717171] flex justify-end relative right-[0.75rem] bottom-[0.1rem]">
                {{ $product->updated_at->timezone('Europe/Amsterdam')->format('d-m-y H:i') }}
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