<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $sidenavActive }}</title>
    <x-layout._header-dependencies :sidenavActive="$sidenavActive" />
</head>

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
    <x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :activeWorkspace="$activeWorkspace" :sidenavActive="$sidenavActive"/>
        </div>
		<!-- when component called, elements placed in component wil go here -->
        {{ $slot }}
    </div>
    
    <x-layout._footer-dependencies />
    <script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/js/product/create-simple.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>
</body>
</html>
