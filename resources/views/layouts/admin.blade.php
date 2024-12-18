<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Global Styles -->
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link href="{{ url('css/home.css') }}" rel="stylesheet">
        <link href="{{ url('css/search.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/admindashboard.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/adminchangeprofile.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/adminproduct.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/admincreate.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/login.css') }}" rel="stylesheet">
        <link href="{{ url('css/pages/orders.css') }}" rel="stylesheet">


        <!-- Page-Specific Styles -->
        @yield('styles')

        <!-- Font Awesome (for icons) -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">



        <!-- Global Scripts -->
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
        <script type="text/javascript" src="{{ url('js/type.js') }}" defer></script>


    </head>
    <body>
        <div class="page-container">
            <!-- Header -->
            <header class="main-header">
                <div class="header-content">
                    <!-- Left Section (Logo) -->
                    <div class= "header-title"><h1><a href="{{ url('/admin/dashboard/users') }}">Console Quest</a></h1></div>

                    <!-- Search Section -->
                    <div class="admin-search-container">
                        <form id="adminsearchForm" method="POST" action="{{ url('/admin/dashboard/users') }}">
                            @csrf
                            <input type="text" name="query" id="query" value="{{ $query ?? '' }}" placeholder="Search...">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Right Section (User Actions) -->
                    <div class="user-actions">
                        <a href="{{ url('/logout') }}" class="auth-link logout-link">Log Out</a>
                    </div>
                </div>
            </header>

            <nav class="main-nav">
                <a href="{{ route('admin.dashboard.users') }}">Users</a>
                <a href="{{ route('admin.dashboard.products') }}">Products</a>
                <a href="{{ route('admin.dashboard.reports') }}">Reports</a>
            </nav>

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
        </div>    

        <!-- Page-Specific Scripts -->
        @yield('scripts')
    </body>
</html>
