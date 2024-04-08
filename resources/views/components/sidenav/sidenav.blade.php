@props(['sidenavActive'])

<?php
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
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png', 'url' => '/users', 'id' => '12', 'slug' => 'users']
    ];

    // session_start();
    
    // if (!isset($_SESSION['count']))
    // {
    // $_SESSION['count'] = 1;
    // }
    // else
    // {
    // ++$_SESSION['count'];
    // }
    
    // echo $_SESSION['count'];
?>

<style>
    .side-nav {
        width: 17.06rem;
        position: relative;
        transition: all 500ms ease;
        top: 0;
    }

    .category-item {
        width: 100%;
    }

    
    .side-nav.close-sidenav {
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


<div id="sidenav-container" class="side-nav bg-white fixed top-0 basic:z-[998] z-[1001] basic:h-[49rem] hd:h-[68rem] uhd:h-[74.9rem]">
    <ul class="category-list flex grid items-center">
        @foreach ($sidenavButtons as $button)
            <?php
                $isActive = isset($button['slug']) && strpos($button['slug'], $sidenavActive) !== false;
                
                $buttonUrl = isset($button['url']) ? url($button['url']) : null;
                $buttonId = isset($button['id']) ? url($button['id']) : null;
            ?>
            <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" slug="{{ $button['slug'] }}" active="{{ $isActive }}" href="{{ $buttonUrl }}">
                {{ $button['text'] }}
            </x-sidenav.sidenav-item>
        @endforeach
    </ul>
    <div class="w-full pt-8 flex justify-center items-center">  
        <button class="open-button arrow-transition" id="setSessionBtn">
            <img class="select-none w-4.5 h-4.5" src="../images/double-arrow-left.png" alt="Arrow down">
        </button>
    </div>
</div>