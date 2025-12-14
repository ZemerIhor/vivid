<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Storefront</title>
    <meta name="description" content="Example of an ecommerce storefront built with Lunar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen antialiased text-gray-900 relative 111">
@livewire('components.navigation')
<main>
    {{ $slot }}
</main>
@livewireScripts
<x-footer/>

</body>
</html>
