<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | AAGLS</title>

<link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
            color: #333;
        }

        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        img {
            max-width: 150px;
            margin-bottom: 20px;
            animation: rotate 6s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #007bff;
        }

        .nav-links a {
            margin: 0 15px;
            padding: 12px 30px;
            font-size: 16px;
            color: white;
            background-color: #333; /* Set button color to black */
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .nav-links a:hover {
            background-color: #000; /* Darker black on hover */
            transform: translateY(-2px); /* Slight lift on hover */
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Rotating custom logo -->
        <img src="{{ asset('img/logo.png') }}" alt="My Custom Logo" >
            <h1></h1>
        <!-- Login and Register links -->
        @if (Route::has('login'))
            <div class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <!-- Footer -->
        <footer>
           <!-- &copy; {{ date('Y') }} My Custom App. All rights reserved.-->
        </footer>
    </div>
</body>
</html>
