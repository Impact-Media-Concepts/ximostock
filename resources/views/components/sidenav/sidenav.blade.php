@php
    $sidenavButtons = [
        ['text' => 'Dashboard', 'icon' => '../images/dashboard-icon.png'],
        ['text' => 'Producten', 'icon' => '../images/product-icon.png'],
        ['text' => 'Logboek', 'icon' => '../images/logbook-icon.png'],
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png'],
        ['text' => 'Verkoopkanelen', 'icon' => '../images/settings-icon.png'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png'],
        ['text' => 'Filters', 'icon' => '../images/properties-icon.png'],
        ['text' => 'Opslaglocaties', 'icon' => '../images/store-location-icon.png'],
        ['text' => 'Thema', 'icon' => '../images/thema-icon.png'],
        ['text' => 'Leveranciers', 'icon' => '../images/suppliers-icon.png'],
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png'],
    ];
@endphp

<div class="w-[17rem] bg-black relative" style="height: 100% ">

    <div class="bg-white relative mt-[5.03rem] flex-col flex items-center">

        <div class="h-[8.31rem] w-[17.12rem] flex justify-center items-center">
            <img class="w-[11.37rem] h-[2.43rem] flex" src="../images/placeholder-logo.png" alt="placeholder logo">
        </div>
        <div class="underline w-[14.12rem] h-[0.08rem] bg-gray-200 mb-4">
        </div>
        {{-- <div>
            @foreach ($sidenavButtons as $button)
                <x-sidenav.sidenav-item icon="{{ $button['icon'] }}">
                    {{ $button['text'] }}
                    <x-sidenav.sidenav-item />
            @endforeach
        </div> --}}

    </div>
</div>
