@component('myriad-components::' . $key)

    @foreach($slots as $slot => $value)

        @if($slot === 'default')
            {{ $value }}
        @else
            @slot($slot) {{ $value }} @endslot
        @endif

    @endforeach

@endcomponent

@foreach($variables as $variable => $value)

    @component('myriad-components::' . $key, [$variable => $value])

        @foreach($slots as $slot => $value)

            @if($slot === 'default')
                {{ $value }}
            @else
                @slot($slot) {{ $value }} @endslot
            @endif

        @endforeach

    @endcomponent

@endforeach


