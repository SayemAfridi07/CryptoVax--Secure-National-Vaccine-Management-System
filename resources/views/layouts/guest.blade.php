<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Background styling */
        body {
            background-image: url('{{ asset('img/bgg1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* White margin container */
        .white-margin-container {
            background-color: rgb(252, 214, 196);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Pop-up animation for heading */
        @keyframes popup {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Styling for the heading */
        .heading {
            font-size: 3rem;
            font-weight: 800;
            color: #ff0303;
            text-align: center;
            margin-bottom: 30px;
            animation: popup 1.5s ease-out;
        }

        /* Styling for the login box */
        .login-box {
            font-size: 1rem;
            color: #4B5563;
            text-align: center;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        /* Hover effect for login box */
        .login-box:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <!-- White Margin Container -->
    <div class="white-margin-container">
        <!-- Heading with Pop-Up Animation -->
        <div class="heading">
            Your Health Is Your Greatest Wealth!
        </div>

        <!-- Login Box -->
        <div class="login-box">
            <!-- Login Form -->
            {{ $slot }}
        </div>
    </div>
</body>
</html>
