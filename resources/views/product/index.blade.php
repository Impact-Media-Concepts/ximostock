<x-layout._header-dependencies />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:67.5rem">
    <x-layout._sidenav-header />
    <x-product.product-container :perPage="$perPage" :products="$products" />

    <div class="w-[16.56rem] h-[59.25rem] flex bg-green-200 my-[7rem] flex-col" style="padding: 20px 20px; gap: 20px">
        <x-product.product-categories />
        <x-product.product-properties />
    </div>
    {{-- Test Bulk discount --}}
    {{-- <h2>discount test</h2>
    <form action="{{ route('products.bulkDiscount') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[{{ $product->id }}][discount]" value='17.00'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="discount selected products">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>

    <h2>test bulk link verkoop kanaal</h2>
    <form action="{{ route('products.bulkLinkSalesChannel') }}" method="POST">
        @csrf
        <h3>products</h3>
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <h3>sales channels</h3>
        <ul>
            @foreach ($sales_channels as $sales_channel)
                <li>
                    <input type="checkbox" name="sales_channel_ids[]" value='{{ $sales_channel->id }}'>
                    {{ $sales_channel->name }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk link saleschannels">
    </form> --}}

    {{-- test --}}
    {{-- <h2>test bulk unlink verkoop kanaal</h2>
    <form action="{{ route('products.bulkUnlinkSalesChannel') }}" method="POST">
        @csrf
        <h3>products</h3>
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <h3>sales channels</h3>
        <ul>
            @foreach ($sales_channels as $sales_channel)
                <li>
                    <input type="checkbox" name="sales_channel_ids[]" value='{{ $sales_channel->id }}'>
                    {{ $sales_channel->name }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk unlink saleschannels">
    </form> --}}

    {{-- test enable backorders --}}
    {{-- <h3>bulk enable backorders</h3>
    <form action="{{ route('products.bulkEnableBackorders') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk enable backorder">
    </form> --}}

    {{-- test disable backorders --}}
    {{-- <h3>bulk disable backorders</h3>
    <form action="{{ route('products.bulkDisableBackorders') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk disable backorder">
    </form> --}}


    {{-- test disable backorders --}}
    {{-- <h3>bulk disable communicate stock</h3>
    <form action="{{ route('products.bulkEnableCommunicateStock') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk enable keep stock ">
    </form> --}}

    {{-- test disable backorders --}}
    {{-- <h3>bulk disable communicate stock</h3>
    <form action="{{ route('products.bulkDisableCommunicateStock') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{ $product->title }}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk disable keep stock ">
    </form> --}}

    {{-- categories --}}
    {{-- <h2>categories</h2>
    <x-categories :categories="$categories" />

    <h1>eigenschappen/filters</h1>
    <ul>
        @foreach ($properties as $property)
            <li>
                {{ $property->name . '  ' . $property->values->type }}
                @if ($property->values->type == 'multiselect' || $property->values->type == 'singelselect')
                    <ol>
                        @foreach ($property->values->options as $option)
                            <li>
                                {{ $option }}
                            </li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
    </ul> --}}
</body>

</html>
