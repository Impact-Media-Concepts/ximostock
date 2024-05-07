<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<?php
    $simple = 'Simple';
?>

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :activeWorkspace="$activeWorkspace" :sidenavActive="$sidenavActive" />
        </div>
        <div class="pt-[1.5rem] basic:w-[71rem] hd:w-[98rem] uhd:w-[138rem] basic:ml-[4rem] hd:ml-0 uhd:ml-0">
            <div>
                <x-product.create.header>{{ $simple }}</x-product.create.header>
                
                <form method="POST" action="/products" enctype="multipart/form-data">
                    @csrf

                    <div id="stepOne" class="step">
                        <x-product.create.simple.stepOne.index />
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
                        <x-product.create.stock.stock :locations="$locations" />
                    </div>

                    <div id="stepSix" class="step" style="display: none;">
                        <x-product.create.salesChannels.sales-channels :salesChannels="$salesChannels" />
                    </div>

                    <x-product.create.create-error-message :errors="$errors"/>

                    <div id="create.simple.ButtonContainer" class="flex w-full items-center bg-white rounded-b-lg h-[6rem] create.simple.-button-container-border">
                        <div id="prevBtn" class="relative right-[1rem]">
                            <x-product.buttons.create-previous-button />
                        </div>

                        <div class="w-full flex justify-end relative right-[2rem]">
                            <x-product.buttons.create-next-button />
                        </div>

                        <div class="relative right-[1.5rem]">
                            <x-product.buttons.create-save-button />
                        </div>
                    </div>
                </form>
                <x-product.popup.create-product-create-property-popup :properties="$properties"/>
            </div>
        </div>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-simple.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>

</html>
