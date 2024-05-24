<!-- TODO: Discuss if its variable or variant -->
<?php
    $variable = 'Variable';
    $app_url = env('VITE_APP_URL');
?>

<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="pt-[1.5rem] basic:w-[71rem] hd:w-[98rem] uhd:w-[138rem] basic:ml-[4rem] hd:ml-0 uhd:ml-0">
        <div>
            <x-product.create.header>{{ $variable }}</x-product.create.header>
            
            <form method="POST" action="/products/variant/" enctype="multipart/form-data">
                @csrf
                
                <div id="stepOne" class="step">
                    <x-product.create.variable.stepOne.index />
                </div>
                
                <div id="stepTwo" class="step" style="display: none;">
                    <x-product.create.stepTwo.photos />
                </div>
                
                <div id="stepThree" class="step" style="display: none;">
                    <x-product.create.stepThree.categories :categories="$categories" />
                </div>
                
                <div id="stepFour" class="step" style="display: none;">
                    <x-product.create.stepFour.properties :properties="$properties"/>
                </div>
                
                <div id="stepFive" class="step" style="display: none;">
                    <x-product.create.variable.stepFive.variations :salesChannels="$salesChannels" :selectedProperties="$selectedProperties" :properties="$properties" :locations="$locations"/>
                </div>
                
                <div id="stepSix" class="step" style="display: none;">
                    <x-product.create.stock.stock :locations="$locations" />
                </div>
                
                <div id="stepSeven" class="step" style="display: none;">
                    <x-product.create.salesChannels.sales-channels :salesChannels="$salesChannels" />
                </div>
                
                <!-- TODO: show error message, styling dorus wireframe -->
                <x-product.create.create-error-message :errors="$errors"/>
                
                <div id="createButtonContainer" class="flex w-full items-center bg-white rounded-b-lg h-[6rem] create-button-container-border">
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
            <x-product.popup.create-product-create-property-popup :selectedProperties="$selectedProperties" :properties="$properties"/>
            
            <x-product.popup.create-product-variations-add-property-popup :selectedProperties="$selectedProperties" :properties="$properties"/>
        </div>
    </div>
</x-layout._layout>
