@props(['products'])

<div class="relative flex items-center bg-white">
    <form class="relative" action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf
        <ul class="w-[78.81rem] flex-col">
            {{-- @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" />
                    <a href="/products/{{ $product->id }}">
                        {{ $product->title }}
                        <img src="{{ $product->primaryPhoto->url }}" width="200" height="200" />
                        {{ $product->sku . ' voorraad:' . $product->stock . '  Verkocht:' . $product->sales . '   laatst aangepast:' . $product->updated_at->diffForHumans() }}
                        @if ($product->discount != null)
                            <del>
                                {{ ' €' . $product->price }}
                            </del>
                            {{ '€' . $product->discount }}
                            @else{{ ' €' . $product->price }}
                        @endif
                        @if ($product->sales_channels_exists)
                            <strong>online</strong>
                        @else
                            <strong>offline</strong>
                        @endif
                        @if ($product->concept)
                            <strong>Concept</strong>
                        @endif
                        allow backorders:
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
                        @endif
                    </a>
                </li>
            @endforeach --}}

            @foreach ($products as $product)
                <li
                    class="flex h-[4.5rem] py-4 gap-2 items-center justify-center hover:bg-gray-100 transition duration-300">
                    <input class="h-[1.06rem] mx-2" type="checkbox" name="product_ids[]" value="{{ $product->id }}" />
                    <a class="rounded-md border-1 flex gap-10 justify-center items-center w-[2.8rem] h-[2.9rem] mr-2"
                        style="border: 2px solid gray; overflow:visible">
                        <img class="w-[2.3rem] h-[2.3rem] pt-[0.01rem] hover:z-10 hover:w-[17rem] hover:h-[17rem] hover:ml-[14.6rem]"
                            style="max-width: none;" src="{{ $product->primaryPhoto->url }}" />
                    </a>
                    <a class="flex items-center gap-14 justify-center" href="/products/{{ $product->id }}">
                        <div class="w-[6.78rem] h-[1]">
                            {{ $product->sku }}
                        </div>
                        <div class="flex w-[20.25rem] h-[2.62rem]" style="align-items: center">
                            {{ $product->title }}
                        </div>
                        <div class="w-[5.62rem] h-[1.06rem]">
                            @if ($product->discount != null)
                                <del>
                                    {{ ' €' . $product->price }}
                                </del>
                                {{ '€' . $product->discount }}
                            @else
                                {{ ' €' . $product->price }}
                            @endif
                        </div>
                        <div class="w-[3.75rem] h-[1.06rem]">
                            {{ $product->stock }}
                        </div>
                        <div class="w-[3.75rem] h-[1.06rem]">
                            {{ $product->sales }}
                        </div>
                        <div class="flex items-center w-[3.12rem] h-[1.06rem]">
                            @if ($product->sales_channels_exists)
                                <div class="w-1.5 h-1.5 bg-[#3DABD5] rounded-full mt-[0.3rem] px-[0.17rem]"></div>
                                <p class="text-[#3DABD5] z-10 flex items-center relative left-1">
                                    Online
                                </p>
                            @else
                                <div class="w-1.5 h-1.5 bg-[#717171] rounded-full mt-[0.3rem] px-[0.17rem]"></div>
                                <p class="text-[#717171] z-10 flex items-center relative left-1">
                                    Offline
                                </p>
                            @endif
                        </div>
                        <div class="w-[7.56rem] h-[1.06rem] text-[14px] flex">
                            {{ $product->updated_at->format('d-m-Y H:i') }}
                        </div>

                        @if ($product->concept)
                            <strong>Concept</strong>
                        @endif
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
            @endforeach
        </ul>
        {{-- <button class="flex" type="submit">Delete Selected Products</button> --}}
    </form>

</div>
