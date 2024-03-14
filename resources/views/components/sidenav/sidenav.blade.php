{{-- <div x-data={isOpen:true}>
    <div id="sidebar" class="h-screen pt-40 overflow-x-hidden overflow-y-auto transition-all duration-200 bg-green-200"
        :class="isOpen ? 'w-48' : 'w-0'">
        <div class="flex justify-end w-full h-auto p-4 bg-gray-400 -">
            <button @click.prevent="isOpen = !isOpen;">X
            </button>
        </div>
    </div>
    <div id="body" class="w-full h-screen overflow-y-auto transition-all duration-200 bg-red-200">
        <div class="flex justify-start w-full h-auto p-4 -">
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
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png'],
        ['text' => 'Verkoopkanalen', 'icon' => '../images/selling-channels-icon.png', 'url' => '/selling-channels'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png', 'url' => '/categories'],
        ['text' => 'Archief', 'icon' => '../images/archive-icon.png', 'url' => '/archive'],
        ['text' => 'Filters', 'icon' => '../images/properties-icon.png', 'url' => '/properties'],
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
            <div>
                @foreach ($sidenavButtons as $button)
                    <?php
                    $isActive = isset($button['url']) && Str::contains(request()->url(), $button['url']);
                    $buttonUrl = isset($button['url']) ? url($button['url']) : null;
                    ?>
                    <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" active="{{ $isActive }}" href="{{ $buttonUrl }}">
                        {{ $button['text'] }}
                    </x-sidenav.sidenav-item>
                @endforeach
            </div>

            <div class="flex justify-center items-center w-12 h-12 relative top-[4rem]">
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
