@extends('layouts.display')

@section('title', 'Create a Post')

@section('content')
    @livewire('post-form')
@endsection

@section('infobar')
    @include('partials.infobar')
@endsection
