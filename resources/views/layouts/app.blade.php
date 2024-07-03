@php
    $navbaritems = [
        'logo' => [
            'src' => asset('/images/ximostock-logo.png'),
            'alt' => "Ximostock",
            'href' => secure_url('/products'),
        ],
        'buttons' => [
            'add' => [
                'image' => asset('/images/plus-solid.svg'),
                'text' => "Nieuwe toevoegen",
                'href' => secure_url('/products/create'),
                'options' => [
                    'products' => [
                        'text' => "Product",
                        'href' => secure_url('/products/create'),
                        'sub-options' => [
                            'simple' => [
                                'text' => "Eenvoudig",
                                'href' => secure_url('/products/create'),
                            ],
                            'variable' => [
                                'text' => "Variabel",
                                'href' => secure_url('/products/create'),
                            ],
                        ],
                    ],
                    'category' => [
                        'text' => "Categorie",
                        'href' => secure_url('/category/create'),
                    ],
                    'properties' => [
                        'text' => "Eigenschappen",
                        'href' => secure_url('/property/create'),
                    ],
                ]
            ],
            'workspace' => [
                'image' => asset('/images/grid-icon.svg'),
                'text' => "Workspaces",
                'href' => secure_url('/workspaces'),
            ]
        ],
        'select' => [
            'image' => asset('/images/user.svg'),
            'arrow' => asset('/images/chevron-down-dark.svg'),
            'options' => [
                'option1' => [
                    'naam' => 'Acount',
                    'icon' => asset('/images/user-grey.svg'),
                    'href' => secure_url('/acount'),
                ],
                'option2' => [
                    'naam' => 'Instellingen',
                    'icon' => asset('/images/settings-grey.svg'),
                    'href' => secure_url('/settings'),
                ],
                'option3' => [
                    'naam' => 'Uitloggen',
                    'icon' => asset('/images/exit-grey.svg'),
                    'href' => secure_url('/uitloggen'),
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
        <Navbar :items='@json($navbaritems)'></Navbar>
        
        <div id="content">
            <Sidebar :items='@json($sidebarItems)'></Sidebar>
            <main>
                {{-- Content Section --}}
                @yield('content')
            </main>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>
</html>
