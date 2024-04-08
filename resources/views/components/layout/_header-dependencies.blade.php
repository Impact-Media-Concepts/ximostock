@props(['sidenavActive'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $sidenavActive }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Copyright 2024 Shaheen Hyder -->
    <script src="https://cdn.jsdelivr.net/gh/shaheenhyderk/slideon@1.0.2/slideon.js"></script>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('./assets/css/layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./assets/css/product.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./assets/css/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('./assets/css/tailwind.css') }}">
</head>
