@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();

    $navbaritems = [
        'logo' => [
            'src' => file_get_contents('images/default-profile-picture.svg'),
            'alt' => 'Ximostock',
            'href' => secure_url('/products'),
        ],
        'select' => [
            'image' => file_get_contents('images/default-profile-picture.svg'),
            'arrow' => file_get_contents('images/chevron-down-dark.svg'),
            'options' => [
                'Settings' => [
                    'title' => 'Instellingen',
                    'href' => route('profile.edit'),
                    'icon' => file_get_contents('images/settings-grey.svg'),
                ],
                'Logout' => [
                    'title' => 'Uitloggen',
                    'href' => route('logout'),
                    'icon' => file_get_contents('images/exit-grey.svg'),
                ],
            ],
        ],
    ];

    if ($user->role == 'admin') {
        $navbaritems['workspace'] = [
            'image' => file_get_contents('images/grid-icon.svg'),
            'text' => 'Workspaces',
            'href' => secure_url('/workspaces'),
        ];
    }

    $sidebarItems = [
        [
            'url' => route('dashboard'),
            'title' => 'Dashboard',
            'image_url' => file_get_contents('images/sidebar/dashboard.svg'),
        ],
        [
            'url' => route('products.index'),
            'title' => 'producten',
            'image_url' => file_get_contents('images/sidebar/producten.svg'),
        ],

        [
            'url' => route('profile.edit'),
            'title' => 'Instellingen',
            'image_url' => file_get_contents('images/sidebar/instellingen.svg'),
        ],
        [
            'url' => route('saleschannels.index'),
            'title' => 'Verkoopkanalen',
            'image_url' => file_get_contents('images/sidebar/verkoopkanalen.svg'),
        ],
        [
            'url' => route('categories.index'),
            'title' => 'Categorieën',
            'image_url' => file_get_contents('images/sidebar/categorieen.svg'),
        ],
        [
            'url' => route('locations.index'),
            'title' => 'Opslaglocaties',
            'image_url' => file_get_contents('images/sidebar/opslaglocaties.svg'),
        ],
        [
            'url' => route('users.theme'),
            'title' => 'Thema',
            'image_url' => file_get_contents('images/sidebar/thema.svg'),
        ],
        [
            'url' => route('users.index'),
            'title' => 'Gebruikers',
            'image_url' => file_get_contents('images/sidebar/gebruikers.svg'),
        ],
        [
            'url' => route('properties.index'),
            'title' => 'Eigenschappen',
            'image_url' => file_get_contents('images/sidebar/property-icon.svg'),
        ],
        [
            'url' => route('suppliers.index'),
            'title' => 'Leveranciers',
            'image_url' => file_get_contents('images/sidebar/supplier-icon.svg'),
        ],
    ];
    if ($user->role == 'admin') {
        $sidebarItems[] = [
            'url' => route('archive.index'),
            'title' => 'archief',
            'image_url' => file_get_contents('images/sidebar/archive-icon.svg'),
        ];
        $sidebarItems[] =  [
            'url' => route('activity-log.index'),
            'title' => 'Logboek',
            'image_url' => file_get_contents('images/sidebar/logboek.svg'),
    ];
    }

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ximostock</title>
    @routes
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>

<body>

    <div id="app">
        <Navbar :items='@json($navbaritems)' :user='@json($user)'></Navbar>

        <div id="content">
            <Sidebar :items='@json($sidebarItems)'></Sidebar>
            <div id="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Your code here
            var root = document.documentElement;


            root.style.setProperty('--primary', '{{ $user->primary_color }}');
            root.style.setProperty('--secondary', '{{ $user->secondary_color }}');
            root.style.setProperty('--background', '{{ $user->background_color }}');
            root.style.setProperty('--light', '{{ $user->background_color }}');
            root.style.setProperty('--text', '{{ $user->text_color }}');

            //set hoover colours
            function darkenColor(hex, percentage) {
                return `#${hex.slice(1).match(/.{2}/g).map(c =>
                Math.max(0, Math.floor(parseInt(c, 16) * (1 - percentage / 100))).toString(16).padStart(2, '0')).join('')}`;
            }

            root.style.setProperty('--hover-primary', darkenColor('{{ $user->primary_color }}', 10));
            root.style.setProperty('--hover-light', darkenColor('{{ $user->background_color }}', 10));
            root.style.setProperty('--hover-secondary', darkenColor('{{ $user->secondary_color }}', 10));

        });
    </script>

</body>

</html>
