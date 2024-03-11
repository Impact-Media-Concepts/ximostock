{{-- <div x-data={isOpen:true}>
    <div id="sidebar" class="h-screen overflow-y-auto overflow-x-hidden bg-green-200 transition-all duration-200 pt-40"
        :class="isOpen ? 'w-48' : 'w-0'">
        <div class="w-full - h-auto p-4 flex justify-end bg-gray-400">
            <button @click.prevent="isOpen = !isOpen;">X
            </button>
        </div>
    </div>
    <div id="body" class="w-full h-screen overflow-y-auto bg-red-200 transition-all duration-200">
        <div class="w-full - h-auto p-4 flex justify-start">
            <button @click.prevent="isOpen = !isOpen;">X
            </button>
        </div>
    </div>
</div> --}}
@php
    $sidenavButtons = [
        ['text' => 'Dashboard', 'icon' => '../images/dashboard-icon.png', 'url' => '/dashboard'],
        ['text' => 'Producten', 'icon' => '../images/product-icon.png', 'url' => '/products'],
        ['text' => 'Logboek', 'icon' => '../images/logbook-icon.png', 'url' => '/logbook'],
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png', 'url'],
        ['text' => 'Verkoopkanalen', 'icon' => '../images/selling-channels-icon.png', 'url' => '/dashboard'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png', 'url' => '/categories'],
        ['text' => 'Filters', 'icon' => '../images/properties-icon.png', 'url' => '/properties'],
        ['text' => 'tester', 'icon' => '../images/properties-icon.png', 'url' => '/test'],
        ['text' => 'Opslaglocaties', 'icon' => '../images/store-location-icon.png', 'url' => '/store-location'],
        ['text' => 'Thema', 'icon' => '../images/thema-icon.png', 'url' => '/theme'],
        ['text' => 'Leveranciers', 'icon' => '../images/suppliers-icon.png', 'url' => '/suppliers'],
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png', 'url' => '/users'],
    ];
@endphp

<div x-data={isOpen:true}>
    <div class="w-[17.05rem] bg-white h-screen relative transition-all duration-200"
        :class="isOpen ? 'w-[17.05rem]' : 'w-[5rem]'">

        <div class="bg-white h-auto relative mt-[5.03rem] right-[0.2rem] flex-col flex items-center">

            <div class="h-[8.31rem] w-[17.12rem] flex justify-center items-center">
                <img class="w-[11.37rem] h-[2.43rem] flex" src="../images/placeholder-logo.png" alt="placeholder logo">
            </div>
            <div class="underline w-[14.6rem] h-[0.08rem] ml-2 bg-gray-200 mb-4">
            </div>
            <div>
                @foreach ($sidenavButtons as $button)
                    <?php
                    $isActive = isset($button['url']) && Str::contains(request()->url(), $button['url']);
                    ?>
                    <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" active="{{ $isActive }}">
                        {{ $button['text'] }}
                    </x-sidenav.sidenav-item>
                @endforeach
            </div>

            <div class="flex justify-center items-center w-12 h-12 relative top-[3.7rem]">
                <button @click.prevent="isOpen = !isOpen;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
