@extends('myriad::layouts.app')

@section('content')

    @foreach($components as $title => $group)

        @component('myriad::components.title')
            {{ $title }}
        @endcomponent

        <ul>
            @foreach($group as $component)
                <li class="mb-8">
                    <component-preview
                        title="{{ ucwords($component['name']) }}"
                        preview-route="{{ route('myriad.preview') }}"
                        :component='@json($component)'
                        :preview-sizes='@json(config('myriad.sizes', []))'
                    >
                    </component-preview>
                </li>
            @endforeach
        </ul>

    @endforeach

@endsection
