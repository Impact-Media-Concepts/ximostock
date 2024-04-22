@props(['sidenavActive', 'activeWorkspace' => null])

<?php
    $sidenavButtons = [
        ['text' => 'Dashboard', 'icon' => '../images/dashboard-icon.png', 'url' => '/dashboard', 'adminUrl' => $activeWorkspace ? '/dashboard?workspaces=' . $activeWorkspace : '', 'id' => '1', 'slug' => 'dashboard'],
        ['text' => 'Producten', 'icon' => '../images/product-icon.png', 'url' => '/products', 'adminUrl' => $activeWorkspace ? '/products/?workspaces=' . $activeWorkspace : '', 'id' => '2', 'slug' => 'products'],
        ['text' => 'Logboek', 'icon' => '../images/logbook-icon.png', 'url' => '/logbook', 'adminUrl' => $activeWorkspace ? '/logbook/?workspaces=' . $activeWorkspace : '', 'id' => '3', 'slug' => 'logbook'],
        ['text' => 'Instellingen', 'icon' => '../images/settings-icon.png','id' => '4', 'slug' => 'dashboard'],
        ['text' => 'Verkoopkanalen', 'icon' => '../images/selling-channels-icon.png', 'url' => '/saleschannels', 'adminUrl' => $activeWorkspace ? '/saleschannels/?workspaces=' . $activeWorkspace : '', 'id' => '5', 'slug' => 'selling-channels'],
        ['text' => 'Categorieën', 'icon' => '../images/dashboard-icon.png', 'url' => '/categories', 'adminUrl' => $activeWorkspace ? '/categories/?workspaces=' . $activeWorkspace : '', 'id' => '6', 'slug' => 'categories'],
        ['text' => 'Archief', 'icon' => '../images/archive-icon.png', 'url' => '/archive', 'adminUrl' => $activeWorkspace ? '/archive/?workspaces=' . $activeWorkspace : '', 'id' => '7', 'slug' => 'archive'],
        ['text' => 'Eigenschappen', 'icon' => '../images/properties-icon.png', 'url' => '/properties', 'adminUrl' => $activeWorkspace ? '/properties/?workspaces=' . $activeWorkspace : '', 'id' => '8', 'slug' => 'properties'],
        ['text' => 'Opslaglocaties', 'icon' => '../images/store-location-icon.png', 'url' => '/store-location', 'adminUrl' => $activeWorkspace ? '/store-location/?workspaces=' . $activeWorkspace : '', 'id' => '9', 'slug' => 'store-location'],
        ['text' => 'Thema', 'icon' => '../images/thema-icon.png', 'url' => '/theme', 'adminUrl' => $activeWorkspace ? '/theme/?workspaces=' . $activeWorkspace : '', 'id' => '10', 'slug' => 'theme'],
        ['text' => 'Leveranciers', 'icon' => '../images/suppliers-icon.png', 'url' => '/suppliers', 'adminUrl' => $activeWorkspace ? '/suppliers/?workspaces=' . $activeWorkspace : '', 'id' => '11', 'slug' => 'suppliers'],
        ['text' => 'Gebruikers', 'icon' => '../images/users-icon.png', 'url' => '/users', 'adminUrl' => $activeWorkspace ? '/users/?workspaces=' . $activeWorkspace : '', 'id' => '12', 'slug' => 'users']
    ];
?>

<style>
    .side-nav {
        position: relative;
        top: 0;
    }

    .rotate-arrows {
        transform: rotate(-180deg);
    }

    .rotate-arrows222 {
        transform: rotate(180deg);
    }

    .arrow-transition {
        transition: transform 0.3s ease-in-out;
    }
    
    .rectangle {
        width: 4.06rem;
        transition: all 500ms ease;
    }

    .rectangle.large {
        width: 17.06rem;
    }
</style>

<div class="hd:hidden uhd:hidden basic-bg basic:h-[61rem] w-full bg-black bg-opacity-75 basic:z-[997] absolute top-0">
</div>

<div id="sidenavContainer" class="<?php echo isset($_COOKIE['sidenavContainer_width']) && $_COOKIE['sidenavContainer_width'] === 'large' ? 'rectangle' : 'rectangle large'; ?> side-nav bg-white fixed top-0 basic:z-[997] z-[1001] basic:h-[56rem] hd:h-[68rem] uhd:h-[74.9rem]">
    <ul class="category-list flex grid items-center">
        @can('index-workspaces')
            @foreach ($sidenavButtons as $button)
                <?php
                    $isActive = isset($button['slug']) && strpos($button['slug'], $sidenavActive) !== false;
                    
                    $buttonUrl = isset($button['adminUrl']) ? url($button['adminUrl']) : null;
                    $buttonId = isset($button['id']) ? url($button['id']) : null;
                ?>
                <x-sidenav.sidenav-item icon="{{ $button['icon'] }}" slug="{{ $button['slug'] }}" active="{{ $isActive }}" href="{{ $buttonUrl }}">
                    {{ $button['text'] }}
                </x-sidenav.sidenav-item>
            @endforeach
        @endcan

        @cannot('index-workspaces')
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
        @endcannot
    </ul>
    <div class="w-full pt-8 flex justify-center items-center">  
        <button onclick="toggleWidth()" class="<?php echo isset($_COOKIE['openButton_rotate']) && $_COOKIE['openButton_rotate'] === 'arrows' ? '' : 'rotate-arrows'; ?> flex justify-center items-center arrow-transition" id="openSideNavButton">
            <img id="arrowIcon" class="select-none w-4.5 h-4.5 <?php echo (isset($_COOKIE['openButton_rotate']) && $_COOKIE['openButton_rotate'] === '') || (isset($_COOKIE['sidenavContainer_width']) && $_COOKIE['sidenavContainer_width'] === '') ? '' : 'rotate-arrows222'; ?>" src="../images/double-arrow-left.png" alt="Arrow down">
        </button>
    </div>
</div>
