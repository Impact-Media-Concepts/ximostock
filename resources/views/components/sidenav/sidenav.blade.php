@props(['sidenavActive'])

@php
    $sidenavButtons = [
        ['text' => 'Dashboard', 'icon' => '../images/dashboard-icon.png', 'url' => '/dashboard', 'id' => '1', 'slug' => 'dashboard'],
        ['text' => 'Producten', 'icon' => '../images/product-icon.png', 'url' => '/products',  'id' => '2', 'slug' => 'products'],
        ['text' => 'Logboek', 'icon' => '../images/logbook-icon.png', 'url' => '/logbook', 'id' => '3', 'slug' => 'logbook'],
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png','id' => '4', 'slug' => 'dashboard'],
        ['text' => 'Verkoopkanalen', 'icon' => '../images/selling-channels-icon.png', 'url' => '/selling-channels', 'id' => '5', 'slug' => 'selling-channels'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png', 'url' => '/categories', 'id' => '6', 'slug' => 'categories'],
        ['text' => 'Archief', 'icon' => '../images/archive-icon.png', 'url' => '/archive', 'id' => '7', 'slug' => 'archive'],
        ['text' => 'Eigenschappen', 'icon' => '../images/properties-icon.png', 'url' => '/properties', 'id' => '8', 'slug' => 'properties'],
        ['text' => 'Opslaglocaties', 'icon' => '../images/store-location-icon.png', 'url' => '/store-location', 'id' => '9', 'slug' => 'store-location'],
        ['text' => 'Thema', 'icon' => '../images/thema-icon.png', 'url' => '/theme', 'id' => '10', 'slug' => 'theme'],
        ['text' => 'Leveranciers', 'icon' => '../images/suppliers-icon.png', 'url' => '/suppliers', 'id' => '11', 'slug' => 'suppliers'],
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png', 'url' => '/users', 'id' => '12', 'slug' => 'users'],
    ];
@endphp

<style>
    .side-bar {
        width: 17.06rem;
        height: 100%;
        position: relative;
        transition: all 500ms ease;
        top: 0;
        z-index: 20;
    }

    .category-item {
        width: 100%;
    }

    .side-bar.close-sidenav {
        width: 4.06rem;
    }

    .activeItem.close-sidenav {
        width: 4.06rem;
    }

    .hide-text {
        display: none !important;
    }

    .rotate-arrows {
        transform: rotate(-180deg);
    }

    .arrow-transition {
        transition: transform 0.2s ease-in-out;
    }
</style>

<div class="side-bar w-[17.06rem] h-full bg-white fixed top-0 z-[1000]">
    <ul class="category-list flex grid items-center">
        @foreach ($sidenavButtons as $button)
            <?php
                $isActive = isset($button['slug']) && strpos($button['slug'], $sidenavActive) !== false;
                
                $buttonUrl = isset($button['url']) ? url($button['url']) : null;
                $buttonId = isset($button['id']) ? url($button['id']) : null;
            ?>
            <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" active="{{ $isActive }}" href="{{ $buttonUrl }}"
            >
                {{ $button['text'] }}
            </x-sidenav.sidenav-item>
        @endforeach
    </ul>
    <div class="w-full pt-8 flex justify-center items-center">  
        <button class="open-button arrow-transition">
            <img class="w-4.5 h-4.5" src="../images/double-arrow-left.png">
        </button>
    </div>
</div>