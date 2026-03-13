<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GESPARC') }}</title>

        <!-- Polices -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Couleurs personnalisées Tailwind
            document.documentElement.style.setProperty('--ci-green', '#009E60');
            document.documentElement.style.setProperty('--ci-orange', '#F77F00');
        </script>
        
        <style>
            .bg-ci-green { background-color: #009E60; }
            .bg-ci-orange { background-color: #F77F00; }
            .text-ci-green { color: #009E60; }
            .text-ci-orange { color: #F77F00; }
            .border-ci-green { border-color: #009E60; }

            /* Suppression du focus bleu et application du orange theme */
            input:focus, select:focus, textarea:focus {
                outline: none !important;
                box-shadow: none !important;
                border-color: #F77F00 !important;
                --tw-ring-width: 0px !important;
                ring: 0 !important;
            }

            /* Correction de la flèche de sélection en double */
            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: none !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen bg-white">
        {{ $slot }}
    </body>
</html>
