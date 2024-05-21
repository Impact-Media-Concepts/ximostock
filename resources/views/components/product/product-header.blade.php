@php
    $headerButtons = [
        ['text' => 'Import', 'width' => '6.31rem', 'icon' => '../images/import-icon.png'],
        ['text' => 'Export', 'width' => '6.31rem', 'icon' => '../images/export-icon.png'],
    ];
@endphp

<div class="w-full h-[4.65rem] flex items-center bg-[#3DABD5] rounded-t-lg pt-1">
    <h1 class="pl-[1.55rem] w-56 pb-[0.2rem] text-white text-[18px]"
        style="font-family: 'Inter', sans-serif; font-weight:bold">
        <p>
            Main product page
        </p>
    </h1>
    <div class="flex justify-end w-full items-center gap-[0.7rem] mt-[0.2rem] pb-[0.15rem] text-white pr-7"
        style="font-family: 'Inter', sans-serif;">
        @foreach ($headerButtons as $button)
            <x-product.buttons.product-header-button class="w-[{{ $button['width'] }}]"  icon="{{ $button['icon'] }}">
				<!-- Gets the button, from headerButtons and takes the text key -->
                {{ $button['text'] }}
            </x-product.buttons.product-header-button>
        @endforeach
    </div>
</div>
