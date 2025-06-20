@extends('layouts.display')

@section('title', 'Edit Post')

@section('content')
    @livewire('post-form', ['post' => $post])
@endsection

@section('infobar')
    @include('partials.infobar')
@endsection
