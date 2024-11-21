<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link href="{{ url('css/home.css') }}" rel="stylesheet">
        <link href="{{ url('css/search.css') }}" rel="stylesheet">

        <!-- Font Awesome (for icons) -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
        </script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    </head>
    <body>
        <!-- Header -->
        <header class="main-header">
            <div class="header-content">
                <!-- Left Section (Logo) -->
                <h1><a href="{{ url('/home') }}">Console Quest</a></h1>

                <!-- Center Section (Search Bar) -->
                <div class="search-container">
                    <form id="searchForm" method="POST" action="{{ url('/home') }}">
                        @csrf
                        <input type="text" name="query" id="query" value="{{ $query ?? '' }}" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Right Section (Navigation and User Actions) -->
                <nav class="header-nav">
                    <a href="{{ url('/home') }}">Home</a>
                    <a href="{{ url('/controllers') }}">Controllers</a>
                    <a href="{{ url('/games') }}">Games</a>
                    <a href="{{ url('/consoles') }}">Consoles</a>
                </nav>

                <div class="user-actions">
                    <a href="{{ url('/wishlist') }}" class="icon"><i class="fas fa-heart"></i></a>
                    <a href="{{ url('/shoppingcart') }}" class="icon"><i class="fas fa-shopping-cart"></i></a>
                    @if (Auth::check())
                        <a href="{{ url('/profile') }}" class="icon"><i class="fas fa-user"></i></a>
                        <a href="{{ url('/logout') }}">Logout</a>
                    @else
                        <a href="{{ url('/login') }}">Sign In</a> | <a href="{{ url('/register') }}">Sign Up</a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <section id="content">
                @yield('content')
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <div class="footer-links">
                <a href="{{ url('/about-us') }}">About Us</a>
                <a href="{{ url('/terms') }}">Terms and Conditions</a>
                <a href="{{ url('/faqs') }}">FAQs</a>
            </div>
            <p>&copy; {{ date('Y') }} Console Quest. All rights reserved.</p>
        </footer>
    </body>
</html>
