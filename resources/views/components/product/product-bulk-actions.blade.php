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
            <span id="selectedCount">0</span> variaties van de <span>{{ $perPage }}</span> geselecteerd.
        </p>
        <p class="text-[#3DABD5] ml-1 cursor-pointer">
            Selecteer alle <span>{{ $perPage }}</span> variaties
        </p>
    </div>
    <div class="flex justify-center items-center pt-1 gap-[0.57rem]">
        @foreach ($buttons as $button)
            <x-product.buttons.product-bulk-button class="w-[{{ $button['width'] }}] {{ $button['text'] === 'Archiveren' ? 'cd-popup-trigger' : '' }}">
                {{ $button['text'] }}
            </x-product.buttons.product-bulk-button>
        @endforeach
    </div>

</div>


<script>

</script>