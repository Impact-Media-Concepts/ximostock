<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title> Ximostock</title>
        @vite('resources/css/app.css')
    </head>
    <body id="app">
        <div class="navbar">Navbar</div>
        <div class="sidebar">
            <a href="/products" class="link">Index</a>
             --------
            <a href="/products/4" class="link">Product</a>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </body>
    @vite('resources/js/app.js')
</html>
