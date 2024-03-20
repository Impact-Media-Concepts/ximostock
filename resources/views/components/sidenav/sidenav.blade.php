@php
    $sidenavButtons = [
        ['text' => 'Dashboard', 'icon' => '../images/dashboard-icon.png', 'url' => '/dashboard', 'id' => '1'],
        ['text' => 'Producten', 'icon' => '../images/product-icon.png', 'url' => '/products',  'id' => '2'],
        ['text' => 'Logboek', 'icon' => '../images/logbook-icon.png', 'url' => '/logbook', 'id' => '3'],
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png','id' => '4'],
        ['text' => 'Verkoopkanalen', 'icon' => '../images/selling-channels-icon.png', 'url' => '/selling-channels', 'id' => '5'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png', 'url' => '/categories', 'id' => '6'],
        ['text' => 'Archief', 'icon' => '../images/archive-icon.png', 'url' => '/archive', 'id' => '7'],
        ['text' => 'Eigenschappen', 'icon' => '../images/properties-icon.png', 'url' => '/properties', 'id' => '8'],
        ['text' => 'Opslaglocaties', 'icon' => '../images/store-location-icon.png', 'url' => '/store-location', 'id' => '9'],
        ['text' => 'Thema', 'icon' => '../images/thema-icon.png', 'url' => '/theme', 'id' => '10'],
        ['text' => 'Leveranciers', 'icon' => '../images/suppliers-icon.png', 'url' => '/suppliers', 'id' => '11'],
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png', 'url' => '/users', 'id' => '12'],
    ];
@endphp

<div x-data={isOpen:true} class="flex">
    <div class="w-[17.05rem] bg-white h-screen relative transition-all duration-200"
        :class="isOpen ? 'w-[17.05rem]' : 'w-[4.08rem]'">

        <div class="bg-white h-auto relative right-[0.2rem] flex-col flex items-center">
            <div >
                @foreach ($sidenavButtons as $button)
                    <?php
                        $isActive = isset($button['url']) && Str::contains(request()->url(), $button['url']);
                        $buttonUrl = isset($button['url']) ? url($button['url']) : null;
                        $buttonId = isset($button['id']) ? url($button['id']) : null;
                    ?>
                    <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" active="{{ $isActive }}" href="{{ $buttonUrl }}">
                        {{ $button['text'] }}
                    </x-sidenav.sidenav-item>
                @endforeach
            </div>

            <div id="closeButton" class=" closeButton flex justify-center items-center w-12 h-12 relative top-[4.3rem] left-[0.08rem]">
                <button>
                    <div class="flex">
                        <img class="w-4.5 h-4.5 delay-[140ms]" :class="{ 'rotate-180': !isOpen }" src="../images/double-arrow-left.png">
                    <div>
                </button>
                <!-- <button @click.prevent="isOpen = !isOpen;">
                    <div class="flex">
                        <img class="w-4.5 h-4.5 delay-[140ms]" :class="{ 'rotate-180': !isOpen }" src="../images/double-arrow-left.png">
                    <div>
                </button> -->
            </div>
        </div>
    </div>
</div>
