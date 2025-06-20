@extends('layouts.display')

@section('title', 'Post Details')

@section('content')
<!--show the post itself-->
    <div class="post-details ml-0 md:ml-56 mr-5">
        @include('partials._post', ['post' => $post, 'isClickable' => false, 'showHover' => false])

        <div>
            <livewire:comments :commentable="$post" wire:key="comments-{{ $post->id }}" />
        </div>
    </div>
@endsection

@section('infobar')
    @include('partials.infobar')
@endsection
