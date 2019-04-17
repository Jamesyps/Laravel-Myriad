<li class="pt-2 pl-2 text-sm">
    <a href="{{ route('myriad.root', ['namespace' => $v['key']]) }}" class="{{ request()->query('namespace') === $v['key'] ? 'font-bold text-blue-500' : 'text-blue-700' }}">
        {{ $k === '*' ? 'Root' : ucwords($k) }}
    </a>

    @if(isset($v['children']))
        <ul>
            @foreach($v['children'] as $k => $v)
                @include('myriad::partials.navigation', ['k' => $k, 'v' => $v])
            @endforeach
        </ul>
    @endif

</li>
