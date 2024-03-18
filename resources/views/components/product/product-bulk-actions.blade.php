@props(['products', 'perPage'])

@php
    $buttons = [
        ['text' => 'Korting', 'width' => '5.688rem'],
        ['text' => 'Verkoopkanalen', 'width' => '9.31rem'],
        ['text' => 'Voorraad activeren', 'width' => '10.5rem'],
        ['text' => 'Archiveren', 'width' => '7.12rem']
    ];
@endphp

<div id="productBulkActionsContainer" class="bulk-actions-hidden bg-[#F8F8F8] h-[3.55rem] flex justify-start items-center pb-1" style="font-family: 'Inter', sans-serif;">
    <div class="flex ml-[2.2rem] mr-4 text-[14px] pt-0.5">
        <p>
            <span id="selectedCount">0</span> producten van de <span>{{ $perPage }}</span> geselecteerd.
        </p>
        <p id="bulkActionsCheckboxSubheaderSelectAll" class="text-[#3DABD5] ml-1 cursor-pointer">
            Selecteer alle <span>{{ $perPage }}</span> producten
        </p>
    </div>
    
    <div class="flex justify-center items-center pt-1 gap-[0.57rem]">
        @foreach ($buttons as $button)
            <x-product.buttons.product-bulk-button class="w-[{{ $button['width'] }}] {{ $button['text'] === 'Archiveren' ? 'cd-popup-trigger' : '' }}  {{ $button['text'] === 'Korting' ? 'discount-popup-trigger' : '' }}">
                {{ $button['text'] }}
            </x-product.buttons.product-bulk-button>
        @endforeach
    </div>
    <x-product.popup.product-discount-popup />
    <x-product.popup.product-archive-popup />
</div>

<script>
    //#region pop up
    jQuery(document).ready(function ($) {
      // Open popup
        $(".cd-popup-trigger").on("click", function (event) {
        event.preventDefault();
        $(".cd-popup").removeClass("hidden");
        }); 

      // Close popup
        $(".cd-popup").on("click", function (event) {
        if (
            $(event.target).is(".cd-popup-close") ||
            $(event.target).is(".no")
        ) {
            event.preventDefault();
            $(this).addClass("hidden");
        }
        });
    });
    //#endregion

    jQuery(document).ready(function ($) {
      // Open popup
        $(".discount-popup-trigger").on("click", function (event) {
        event.preventDefault();
        $(".discount-popup").removeClass("hidden");
        }); 

      // Close popup
        $(".discount-popup").on("click", function (event) {
        if (
            $(event.target).is(".discount-popup-close") ||
            $(event.target).is(".discountCancel") ||
            $(event.target).is(".discountSave")
        ) {
            event.preventDefault();
            $(this).addClass("hidden");
        }
        });
    });
</script>