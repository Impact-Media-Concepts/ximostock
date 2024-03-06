@php
    $headerButtons = [
        ['text' => 'Dashboard', 'width' => '6.31rem', 'icon' => '../images/import-icon.png'],
        ['text' => 'Producten', 'width' => '6.31rem', 'icon' => '../images/export-icon.png'],
        ['text' => 'Logboek', 'width' => '10.18rem', 'icon' => '../images/selling-channels-icon.png'],
        ['text' => 'Instellingen', 'width' => '8rem', 'icon' => '../images/archive-icon.png'],
        ['text' => 'Verkoopkanelen', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Categorieën', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Filters', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Opslaglocaties', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Thema', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Thema', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Thema', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
        ['text' => 'Thema', 'width' => '8.43rem', 'icon' => '../images/delete-icon.png'],
    ];
@endphp

<div class="w-[17rem] bg-black relative" style="height: 100% ">

    <div class="bg-white relative mt-[5.03rem] flex-col flex items-center">

        <div class="h-[8.31rem] w-[17.12rem] flex justify-center items-center">
            <img class="w-[11.37rem] h-[2.43rem] flex" src="../images/placeholder-logo.png" alt="placeholder logo">
        </div>
        <div class="underline w-[14.12rem] h-[0.08rem] bg-gray-200 mb-4">
        </div>
        <x-sidenav.sidenav-item />

        @foreach ($buttons as $button)
            <x-product.buttons.product-bulk-button class="w-[{{ $button['width'] }}]">

                <x-sidenav.sidenav-item icon="{{ $button['icon'] }}">
                    {{ $button['text'] }}
                    <x-sidenav.sidenav-item />
        @endforeach
    </div>
</div>
