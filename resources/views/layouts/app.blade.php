<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - {{ config('myriad.title', 'Myriad') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('myriad/css/myriad.css') }}"/>
</head>
<body class="bg-white font-body">

<div id="myriad-app" class="flex flex-wrap">

    <div class="fixed flex py-4 px-6 w-full bg-blue-600 text-white shadow z-10">
        <h1 class="uppercase tracking-wide">
            <a href="{{ route('myriad.root') }}">
                {{ config('myriad.title', 'Myriad') }}
            </a>
        </h1>
    </div>

    <div class="bg-gray-100 border-r border-gray-200 w-64 p-6 pt-20">
        <nav>
            <h1 class="text-sm uppercase tracking-wide text-gray-600">
                Components
            </h1>

            <ul class="-ml-2">
                @foreach($namespaces as $k => $v)
                    @include('myriad::partials.navigation', ['k' => $k, 'v' => $v])
                @endforeach
            </ul>
        </nav>

        @yield('sidebar')
    </div>

    <div class="flex-1 p-6 pt-20 overflow-auto h-screen">
        @yield('content')
    </div>

</div>

<script src="{{ asset('myriad/js/myriad.js') }}"></script>
</body>
</html>
