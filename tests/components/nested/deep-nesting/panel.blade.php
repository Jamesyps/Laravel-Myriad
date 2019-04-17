<div>
    @isset($header)
        <h1>{{ $header }}</h1>
    @endisset

    {{ $slot }}
</div>
