<h1 class="mb-6 text-{{ $size ?? '2xl' }} text-gray-600 font-light tracking-wide {{ $class ?? null }}">
    @if(Str::contains($slot, '*'))
        Root
    @else
        {{ ucwords(Str::contains($slot, '.') ? substr($slot, strrpos($slot, '.') + 1) : $slot) }}
    @endif
</h1>
