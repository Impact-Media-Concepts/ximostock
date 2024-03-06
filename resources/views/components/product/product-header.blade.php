@php
    $headerButtons = [
        ['text' => 'Import', 'width' => '6.31rem', 'icon' => '../images/import-icon.png'],
        ['text' => 'Export', 'width' => '6.31rem', 'icon' => '../images/export-icon.png'],
        ['text' => 'Verkoopkanalen', 'width' => '10.18rem', 'icon' => '../images/selling-channels-icon.png'],
        ['text' => 'Archiveren', 'width' => '8rem', 'icon' => '../images/archive-icon.png'],
        ['text' => 'Verwijderen', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
    ];
@endphp

<div class="w-[78.81rem] h-[4.65rem] flex items-center gap-[24.5rem] bg-[#3DABD5] rounded-t-lg pt-1">
    <h1 class="relative left-[1.55rem] bottom-[0.2rem] text-white text-[18px]"
        style="font-family: 'Inter', sans-serif; font-weight:bold">Main
        product page
    </h1>
    <div class="flex justify-center items-center gap-[0.7rem] pb-[0.15rem] text-white relative left-[0.1rem]"
        style="font-family: 'Inter', sans-serif;">
        @foreach ($headerButtons as $button)
            <x-product.buttons.product-header-button class="w-[{{ $button['width'] }}]" icon="{{ $button['icon'] }}">
                {{ $button['text'] }}
            </x-product.buttons.product-header-button>
        @endforeach
    </div>
</div>
