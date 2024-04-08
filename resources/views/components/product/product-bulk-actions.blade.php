@props(['products', 'perPage', 'discountError'])

@php
    $buttons = [
        ['text' => 'Korting', 'width' => '5.688rem', 'actionId' => 'Discount'],
        ['text' => 'Verkoopkanalen', 'width' => '9.31rem', 'actionId' => 'SalesChannels'],
        ['text' => 'Voorraad communiceren', 'width' => '10.5rem', 'actionId' => 'CommunicateStock'],
        ['text' => 'Voorraad niet communiceren', 'width' => '10.5rem', 'actionId' => 'UncommunicateStock'],
        ['text' => 'Archiveren', 'width' => '7.12rem', 'actionId' => 'Archive']
    ];
@endphp

<div id="productBulkActionsContainer" class="bulk-actions-hidden bg-[#F8F8F8] w-full h-[3.55rem] flex justify-start items-center pb-1" style="font-family: 'Inter', sans-serif;">
    <div class="flex ml-[2.2rem] mr-4 text-[14px] pt-0.5">
        <p>
            <span id="selectedCount">0</span> producten van de <span>{{ $perPage }}</span> geselecteerd.
        </p>
        <p id="selectAll" class="text-[#3DABD5] ml-1 cursor-pointer">
            Selecteer alle <span>{{ $perPage }}</span> producten
        </p>
    </div>
    
    <div class="flex justify-center items-center pt-1 gap-[0.57rem]">
        @foreach ($buttons as $button)
            <x-product.buttons.product-bulk-button
                bulkActionId="bulkAction{{$button['actionId']}}"
                class="w-[{{ $button['width'] }}] {{ $button['text'] === 'Archiveren' ? 'cd-popup-trigger' : '' }}
                {{ $button['text'] === 'Korting' ? 'discount-popup-trigger' : '' }}
                {{ $button['text'] === 'Verkoopkanalen' ? 'sales-channel-popup-trigger' : '' }}
                {{ $button['text'] === 'Voorraad communiceren' ? 'communicateStock' : '' }}
                {{ $button['text'] === 'Voorraad niet communiceren' ? 'uncommunicateStock' : '' }}"
            >
                {{ $button['text'] }}
            </x-product.buttons.product-bulk-button>
        @endforeach
    </div>
    
    <x-product.popup.product-discount-popup :discountError="$discountError" />
    <x-product.popup.product-archive-popup />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let communicateStock = document.querySelectorAll('.communicateStock');
            communicateStock.forEach(function(button) {
                button.addEventListener('click', function() {
                    console.log('clickeds');
                });
            });
        });
    </script>
</div>
