<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    {{-- Vue --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- Compiled CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div id="app">
        @section('navbar')
        
        @endsection

        <div class="container">
            @yield('content')
        </div>

        @section('footer')
        
        @endsection
    </div>
</body>
</html>