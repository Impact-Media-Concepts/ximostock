@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();

    $navbaritems = [
        'logo' => [
            'src' => '/images/ximostock-logo.png',
            'alt' => "Ximostock",
            'href' => secure_url('/products'),
        ],
        'select' => [
            'image' => '/images/default-profile-picture.svg',
            'arrow' => '/images/chevron-down-dark.svg',
            'options' => [
                'Settings' => [
                    'title' => "Instellingen",
                    'href' => route('profile.edit'),
                    'icon' => '/images/settings-grey.svg',
                ],
                'Logout' => [
                    'title' => "Uitloggen",
                    'href' => route('logout'),
                    'icon' => '/images/exit-grey.svg',
                ],
            ],
        ]
    ];

    if ($user->role == 'admin') {
        $navbaritems['add'] = [
            'image' => '/images/grid-icon.svg',
            'text' => "Nieuwe toevoegen",
            'href' => secure_url('/products/create'),
        ];
        $navbaritems['workspace'] = [
            'image' => '/images/grid-icon.svg',
            'text' => "Workspaces",
            'href' => secure_url('/workspaces'),
        ];
    }

    $sidebarItems = [
        [
            'url' => route('dashboard'),
            'title' => 'Dashboard',
            'image_url' => '/images/sidebar/dashboard.svg',
        ],
        [
            'url' => route('products.index'),
            'title' => 'producten',
            'image_url' => '/images/sidebar/producten.svg',
        ],
        [
            'url' => route('activity-log.index'),
            'title' => 'Logboek',
            'image_url' => '/images/sidebar/logboek.svg',
        ],
        [
            'url' => route('dashboard'),
            'title' => 'Instellingen',
            'image_url' => '/images/sidebar/instellingen.svg',
        ],
        [
            'url' => route('saleschannels.index'),
            'title' => 'Verkoopkanalen',
            'image_url' => '/images/sidebar/verkoopkanalen.svg',
        ],
        [
            'url' => route('categories.index'),
            'title' => 'Categorieën',
            'image_url' => '/images/sidebar/categorieen.svg',
        ],
        // [
        //     'url' => secure_url('/archive'),
        //     'title' => 'Archief ()',
        //     'image_url' => '/images/sidebar/archief.svg',
        // ],
        // [
        //     'url' => secure_url('/filter'),
        //     'title' => 'Filter ()',
        //     'image_url' => '/images/sidebar/filters.svg',
        // ],
        // [
        //     'url' => secure_url('/filtersets'),
        //     'title' => 'Filtersets ()',
        //     'image_url' => '/images/sidebar/filtersets.svg',
        // ],
        [
            'url' => route('locations.index'),
            'title' => 'Opslaglocaties',
            'image_url' => '/images/sidebar/opslaglocaties.svg',
        ],
        // [
        //     'url' => secure_url('/dashboard'),
        //     'title' => 'Thema ()',
        //     'image_url' => '/images/sidebar/thema.svg',
        // ],
        // [
        //     'url' => secure_url('/dashboard'),
        //     'title' => 'Leveranciers ()',
        //     'image_url' => '/images/sidebar/leveranciers.svg',
        // ],
        [
            'url' => route('users.index'),
            'title' => 'Gebruikers',
            'image_url' => '/images/sidebar/gebruikers.svg',
        ],
    ];

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ximostock</title>
    @routes
    @vite('resources/css/app.css')
</head>
<body>

    <div id="app">
        <Navbar :items='@json($navbaritems)' :user='@json($user)' ></Navbar>

        <div id="content">
            <Sidebar :items='@json($sidebarItems)'></Sidebar>
            <div id="main-content">
                @yield('content')
            </div>
        </div>

        {{-- @if(session('errors'))
            <general-notification :errors='@json(session('errors'))'></general-notification>
        @endif --}}
    
    </div>

    @vite('resources/js/app.js')
</body>
</html>