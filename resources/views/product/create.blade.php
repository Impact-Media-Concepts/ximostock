<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
        </div>
        <div class="pt-[1.5rem] hd:w-[98rem] uhd:w-[138rem]">
            <div>
                <x-product.create.header />
              
                <form method="POST" action="/products" enctype="multipart/form-data">
                    @csrf

                    <div id="stepOne" class="step">
                        <x-product.create.stepOne.index />
                    </div>

                    <div id="stepTwo" class="step" style="display: none;">
                        <x-product.create.stepTwo.photos />
                    </div>

                    <div id="stepThree" class="step" style="display: none;">
                        <x-product.create.stepThree.categories :categories="$categories" />
                    </div>

                    <div id="stepFour" class="step" style="display: none;">
                        <x-product.create.stepFour.properties :properties="$properties" />
                    </div>

                    <div id="stepFive" class="step" style="display: none;">
                        <x-product.create.stepFive.stock :locations="$locations" />
                    </div>

                    <div id="stepSix" class="step" style="display: none;">
                        <x-product.create.stepSix.sales-channels :salesChannels="$salesChannels" />
                    </div>

                    <x-product.create.create-error-message :errors="$errors"/>

                    <div class="flex w-full items-center">
                        <div id="prevBtn" class="mr-[2rem]">
                            <x-product.buttons.create-previous-button />
                        </div>

                        <div>
                            <x-product.buttons.create-cancel-button />
                        </div>

                        <div class="w-full flex justify-end">
                            <x-product.buttons.create-next-button />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-simple.js') }}"></script>

</html>
