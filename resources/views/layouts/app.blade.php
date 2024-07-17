@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();

    $navbaritems = [
        'logo' => [
            'src' => asset('/images/ximostock-logo.png'),
            'alt' => "Ximostock",
            'href' => secure_url('/products'),
        ],
        'buttons' => [
            'add' => [
                'image' => asset('/images/grid-icon.svg'),
                'text' => "Nieuwe toevoegen",
                'href' => secure_url('/products/create'),
            ],
            'workspace' => [
                'image' => asset('/images/grid-icon.svg'),
                'text' => "Workspaces",
                'href' => secure_url('/workspaces'),
            ]
        ],
        'select' => [
            'image' => asset('/images/default-profile-picture.svg'),
            'arrow' => asset('/images/chevron-down-dark.svg'),
            'options' => [
                'Settings' => [
                    'title' => "Instellingen",
                    'href' => route('profile.edit'),
                    'icon' => asset('/images/settings-grey.svg'),
                ],
                'Logout' => [
                    'title' => "Uitloggen",
                    'href' => route('logout'),
                    'icon' => asset('/images/exit-grey.svg'),
                ],
            ],
        ]
    ];

    $sidebarItems = [
        [
            'url' => secure_url('/dashboard'),
            'title' => 'Dashboard',
            'image_url' => asset('/images/sidebar/dashboard.svg'),
        ],
        [
            'url' => secure_url('/products'),
            'title' => 'producten',
            'image_url' => asset('/images/sidebar/producten.svg'),
        ],
        [
            'url' => secure_url('/activity-log'),
            'title' => 'Logboek',
            'image_url' => asset('/images/sidebar/logboek.svg'),
        ],
        [
            'url' => secure_url('/dashboard'),
            'title' => 'Instellingen',
            'image_url' => asset('/images/sidebar/instellingen.svg'),
        ],
        [
            'url' => secure_url('/saleschannels'),
            'title' => 'Verkoopkanalen',
            'image_url' => asset('/images/sidebar/verkoopkanalen.svg'),
        ],
        [
            'url' => secure_url('/categories'),
            'title' => 'Categorieën',
            'image_url' => asset('/images/sidebar/categorieen.svg'),
        ],
        [
            'url' => secure_url('/archive'),
            'title' => 'Archief ()',
            'image_url' => asset('/images/sidebar/archief.svg'),
        ],
        [
            'url' => secure_url('/filter'),
            'title' => 'Filter ()',
            'image_url' => asset('/images/sidebar/filters.svg'),
        ],
        [
            'url' => secure_url('/filtersets'),
            'title' => 'Filtersets ()',
            'image_url' => asset('/images/sidebar/filtersets.svg'),
        ],
        [
            'url' => secure_url('/locations'),
            'title' => 'Opslaglocaties',
            'image_url' => asset('/images/sidebar/opslaglocaties.svg'),
        ],
        [
            'url' => secure_url('/dashboard'),
            'title' => 'Thema ()',
            'image_url' => asset('/images/sidebar/thema.svg'),
        ],
        [
            'url' => secure_url('/dashboard'),
            'title' => 'Leveranciers ()',
            'image_url' => asset('/images/sidebar/leveranciers.svg'),
        ],
        [
            'url' => secure_url('/users'),
            'title' => 'Gebruikers',
            'image_url' => asset('/images/sidebar/gebruikers.svg'),
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
    </div>
    @vite('resources/js/app.js')
</body>
</html>
