@extends('layouts.display')

@section('title', 'Bloggit - Dive in!')

@section('content')
    <div class="ml-0 md:ml-56">
        <div class="mb-2 flex items-center">
            <!--filters - tags-->
            <form action="{{ route('posts.index') }}" method="GET" class="relative">
                <select name="filter" onchange="this.form.submit()"
                    aria-current="page"
                    class="text-xs appearance-none bg-transparent focus:outline-none focus:ring-2 border-none text-gray-700 pr-8 cursor-pointer rounded-lg hover:bg-gray-100 px-3 py-1 transition-colors duration-200">
                    <option value="all" {{ Request::input('filter') == 'all' ? 'selected' : '' }}>All Posts</option>
                    <option value="Announcement" {{ request('filter') == 'Announcement' ? 'selected' : '' }}>Announcements
                    </option>
                    <option value="Discussion" {{ request('filter') == 'Discussion' ? 'selected' : '' }}>Discussion</option>
                    <option value="Help" {{ request('filter') == 'Help' ? 'selected' : '' }}>Help</option>
                </select>
            </form>
        </div>

        <ul>
            <!--display posts-->
            @foreach ($posts as $post)
                <li class="{{ !$loop->last ? 'border-t' : 'border-t border-b' }} border-[#ddd] relative">
                    @include('partials._post', [
                        'post' => $post,
                        'isClickable' => true,
                        'showHover' => true,
                    ])
                </li>
            @endforeach
        </ul>

        <div class="pagination mt-2">
            {{ $posts->links() }}
        </div>
    </div>
@endsection

<!--display popular posts on the side-->
@section('infobar')

    @include('partials.popular-posts')

@endsection
