@extends('layouts.display')

@section('title', "Tag: $tag->name")

@section('content')

    <div class="ml-0 md:ml-48">
        <h1 class="my-4">Tag: {{ $tag->name }}</h1>
        <!--show posts with related tag-->
        <ul>
            @forelse ($tag->posts as $post)
                <li class="{{ !$loop->last ? 'border-t' : 'border-t border-b' }} border-[#ddd] relative">
                    @include('partials._post', [
                        'post' => $post,
                        'isClickable' => true,
                        'showHover' => true,
                    ])
                </li>

            @empty
                <p>No posts associated with this tag.</p>
            @endforelse
        </ul>

    </div>
@endsection

@section('infobar')
    @include('partials.infobar')
@endsection
