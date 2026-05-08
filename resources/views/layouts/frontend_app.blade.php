<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @stack('css')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            position: relative;
        }

        body {
            margin-bottom: 215px;
        }

        ul {
            list-style: none;
        }

        a {
            text-decoration: none;
            color: #F26D3E;
        }

        section,
        footer {
            padding: 45px 0;
        }

        footer {
            background: #ff8c62;
            color: #fff;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 215px;
        }

        /* Hero section background */
        #hero_section {
            background: url('{{ asset('img/bg-c.jpg') }}') no-repeat center center;
            background-size: cover;
            height: 85vh;
            animation: fadeIn 2s ease-out;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .hero_card {
            padding: 110px 0;
        }

        .usr_icon {
            font-size: 36px;
        }

        nav a {
            font-size: 18px;
            font-weight: 500;
        }

        .items i {
            font-size: 22px;
            color: #F26D3E;
        }
        .nav-item.ms-5 {
        margin-left: 60px; /* Customize spacing as needed */
        }
            /* Navbar Button Animation */
        .navbar-nav .nav-link {
            position: relative;
            display: inline-block;
            padding: 0.5rem 1rem;
            color: #F26D3E;
            font-weight: 500;
            transition: color 0.3s ease;
            overflow: hidden;
        }

        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #F26D3E;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #FF5733;
        }

        .navbar-nav .nav-link:hover::before {
            left: 0;
            width: 100%;
        }

        /* Responsive Toggler Animation */
        .navbar-toggler-icon {
            transition: transform 0.3s ease-in-out;
        }

        .navbar-toggler:hover .navbar-toggler-icon {
            transform: rotate(90deg);
        }


    </style>
    <title>@yield('title')</title>
</head>

<body>

    <header class="navbar-light bg-light py-2">
        <div class="container">
            <div class="row py-1">
                <div class="col-md-4">
                    <a href="/">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo">
                    </a>
                </div>
                <div class="col-md-8 align-self-center align-items-center justify-content-end" style="text-align: center">
                    <nav class="navbar navbar-expand-md">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav" style="margin-left: auto">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                                </li>
                                @if(!auth()?->user())
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{ route('login') }}">Login</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{ route('register') }}">Register</a>
                                    </li>
                                @else
                                    @if (auth()->user()->registration)
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page"
                                                href="{{ route('front.registration.status') }}">Vaccine Status</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page"
                                                href="{{ route('front.vaccine.card') }}">Card</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page"
                                                href="{{ route('front.vaccine.certificate') }}">Certificate</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>

                                        <li class="nav-item ms-5">
                                            <a class="nav-link active" aria-current="page" href="{{ route('profile.edit') }}">
                                                <i class="fa fa-user"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link active" aria-current="page"
                                                href="{{ route('front.registration.create') }}">Apply</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                        <li class="nav-item ms-5">
                                            <a class="nav-link active" aria-current="page" href="{{ route('profile.edit') }}">
                                                <i class="fa fa-user"></i>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main role="main" class="container">
        @yield('app_content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-2 align-self-center">
                    <a href="/">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo">
                    </a>
                </div>
                <div class="col-md-3 mb-2">
                    <ul>
                        <li><a href="" class="text-light">FAQ</a></li>
                        <li><a href="" class="text-light">Help</a></li>
                        <li><a href="" class="text-light">Privacy Policy</a></li>
                        <li><a href="" class="text-light">Terms of Services</a></li>
                        <li><a href="" class="text-light">Other Partner Organizations</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-2 align-self-center">
                    <h6>Powered by - Department of Information and Communication Technology</h6>
                    <img src="{{ asset('img/foo1.png') }}" alt="Partner 1">
                </div>
                <div class="col-md-3 mb-2 align-self-center">
                    <h6>Partner Organizations -</h6>
                    <img src="{{ asset('img/foo2.png') }}" alt="Partner 2">
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"></script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", 'Success!')
        @elseif (Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}", 'Warning!')
        @elseif (Session::has('error'))
            toastr.error("{{ Session::get('error') }}", 'Error!')
        @endif
    </script>
    <script>
        const $$ = (el) => document.querySelector(el);
        const log = (el = 'ok') => console.log(el);
        const base_url = window.location.origin;
    </script>
    @stack('script')
</body>

</html>
