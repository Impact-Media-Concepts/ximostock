@php
    $buttons = [
        ['text' => 'Korting', 'width' => '5.688rem'],
        ['text' => 'Verkoopkanalen', 'width' => '9.31rem'],
        ['text' => 'Voorraad activeren', 'width' => '10.5rem'],
        ['text' => 'Archiveren', 'width' => '7.12rem'],
        ['text' => 'Verwijderen', 'width' => '7.56rem'],
    ];
@endphp

<div class="bg-[#F8F8F8] h-[3.55rem] flex justify-start items-center pb-1" style="font-family: 'Inter', sans-serif;">
    <div class="flex ml-[2.2rem] mr-4 text-[14px] pt-0.5">
        <p>
            10 variaties van de 12 geselecteerd.
        </p>
        <p class="text-[#3DABD5] ml-1 cursor-pointer">
            Selecteer alle 12 variaties
        </p>
    </div>
    <div class="flex justify-center items-center pt-1 gap-[0.57rem]">
        @foreach ($buttons as $button)
            <x-product.buttons.product-bulk-button class="w-[{{ $button['width'] }}]">
                {{ $button['text'] }}
            </x-product.buttons.product-bulk-button>
        @endforeach
    </div>

</div>
