@props(['product'])

<li class="flex h-[4.5rem] py-4 gap-2 items-center justify-center hover:bg-gray-100 transition duration-300">
    <div class="flex items-center relative left-[1.08rem]">
        <div class="flex justify-center items-center w-10 h-[4.5rem] m-0 p-0">
            <input class="h-[1.06rem] mx-2 mt-[0.3rem] checkbox-row flex cursor-pointer relative right-[0.1rem]"
                type="checkbox" name="product_ids[]" value="{{ $product->id }}" />
        </div>

        <a class="rounded-md border-1 flex gap-10 justify-center items-center w-[2.8rem] h-[2.9rem] relative left-0 top-[0.08rem] mr-2"
            style="border: 1px solid #DBDBDB; overflow:visible">
            <img class="w-[2.3rem] h-[2.3rem] pt-[0.01rem]" style="max-width: none;"
                src="{{ $product->primaryPhoto->url }}" />
        </a>
    </div>

    <a class="flex items-center gap-14 justify-center" href="/products/{{ $product->id }}">
        <div class="flex w-[20.25rem] h-[2.62rem] relative left-8" style="align-items: center"
            title=" {{ $product->title }}">
            <p class="line-clamp-2">
                {{ $product->title }}
            </p>
        </div>

        <div class="w-[6.78rem] h-[1] mt-[0.35rem]">
            {{-- {{ $product->sku }} --}}
            @if ($product->sku != null)
                <p>
                    {{ $product->sku }}
                </p>
            @else
                <p>
                    N.V.T.
                </p>
            @endif
        </div>

        <div class="w-[5.62rem] h-[3rem] flex-col relative top-[0.45rem] right-5">
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
        <div class="flex gap-16 relative right-[2.75rem]">
            <div class="w-[3.75rem] h-[1.06rem] relative left-[0.15rem] top-[0.1rem]  ">
                {{-- {{ $product->stock }} --}}
                Voorraad
            </div>
            <div class="flex gap-16 relative left-5">
                <div class="flex w-[3.75rem] relative right-[0.1rem] top-[0.1rem] h-[1.06rem] ">
                    {{-- {{ $product->sales }} --}}
                    Verkocht
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

        </div>
        <div
            class="w-[7.81rem] h-[1.06rem] text-[14px] mt-[0.18rem] text-[#717171] flex relative right-[0.75rem] bottom-[0.1rem]">
            {{ $product->updated_at->format('d-m-y H:i') }}
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
