<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
        </div>
        <div class="pt-20">
            <div>
                <form method="POST" action="/products" enctype="multipart/form-data">
                    @csrf
                    <x-product.create.stepOne.index />

                    <x-product.create.stepTwo.photos />

                    <x-product.create.stepThree.categories :categories="$categories" />

                    <x-product.create.stepFour.properties :properties="$properties" />

                    <x-product.create.stepFive.stock :locations="$locations" />

                    <x-product.create.stepSix.sales-channels :salesChannels="$salesChannels" />

                    <x-product.create.create-error-message :errors="$errors" />

                    <input type="submit" value="Submit"></input>
                </form>
            </div>
        </div>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>

</html>
