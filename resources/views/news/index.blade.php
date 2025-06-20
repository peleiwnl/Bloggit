@extends('layouts.display')

@section('title', 'Bloggit - News')

@section('content')
    <div class="ml-0 md:ml-48">
        @if (isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $error }}
            </div>
        @endif

        <div class="mb-2 flex items-center">
            <h1 class="text-xl font-bold">Latest News</h1>
        </div>

        <ul>
            @foreach ($articles as $article)
                <li class="{{ !$loop->last ? 'border-t' : 'border-t border-b' }} border-[#ddd] relative">
                    @include('partials._article', ['article' => $article])
                </li>
            @endforeach
        </ul>

        <div class="pagination mt-4">
            {{ $articles->links() }}
        </div>
    </div>
@endsection

@section('infobar')
    @include('partials.popular-posts')
@endsection
