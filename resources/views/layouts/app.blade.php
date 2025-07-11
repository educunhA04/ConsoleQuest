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
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/home.css') }}" rel="stylesheet">
    <link href="{{ url('css/search.css') }}" rel="stylesheet">
    <link href="{{ url('css/pages/login.css') }}" rel="stylesheet">
    <link href="{{ url('css/pages/register.css') }}" rel="stylesheet">
    <link href="{{ url('css/pages/wishlist.css') }}" rel="stylesheet">
    <link href="{{ url('css/pages/recoverPass.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



    <!-- Font Awesome (for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <!-- Scripts -->
    <script src="{{ asset('js/order.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>
    <script src="{{ asset('js/wishlist_cart.js') }}" defer></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
    <script>
    const wishlistAddUrl = "{{ route('wishlist.add') }}";
    const cartAddUrl = "{{ route('cart.add') }}";
    </script>
    <script src="{{ asset('js/app.js') }}"></script>


</head>
<body>
    
    @if (Auth::check() && Auth::user()->blocked)
        <script>
            window.location.href = "{{ url('/logout') }}";
        </script>
    @endif


    <div id="notification-container" class="notification-container"></div>
    <div class="page-container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-content">
                <!-- Left Section (Logo) -->
                <div class="header-title"><h1><a href="{{ url('/home') }}">Console Quest</a></h1></div>

                <div class="search-wrapper">
                    @yield('search_and_filter')
                    
                </div>

                <!-- Right Section (User Actions) -->
                <div class="user-actions">
                    <a href="{{ url('/wishlist') }}" class="icon">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="{{ url('/shoppingcart') }}" class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    @if (Auth::check())
                        <a href="{{ url('/profile') }}" class="icon">
                            @if (Auth::user()->image)
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="User Image">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </a>
                        <a href="{{ url('/logout') }}" class="auth-link log-out">Sign Out</a>
                    @else
                        <div class = sign-in-up>
                            <a href="{{ url('/login') }}" class="auth-link sign-in">Sign In</a>
                            <a class="auth-link bar">|</a>
                            <a href="{{ url('/register') }}" class="auth-link sign-up">Sign Up</a>
                        </div>
                    @endif
                </div>

            </div>
        </header>
        @yield('navigation')
        @yield('filters')
        <!-- Main Content -->
        <main>
            <section id="content">
                @yield('content')
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <div class="footer-links">
                <a href="{{ url('/aboutus') }}">About Us</a>
                <a href="{{ url('/terms') }}">Terms and Conditions</a>
                <a href="{{ url('/faqs') }}">FAQs</a>
                <a href="{{ url('/help') }}">Help</a>
            </div>
            <p>&copy; {{ date('Y') }} Console Quest. All rights reserved.</p>
        </footer>
    </div>

    @yield('scripts')

</body>
</html>
