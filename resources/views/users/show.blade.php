@extends('layouts.display')

@section('title', "{$user->name}'s Profile")

<!--users profile-->

@section('content')
    <div class="ml-0 md:ml-48">

        <div class="bg-white rounded-lg mb-6">
            <div class="flex items-center space-x-4">

                <div class="relative">
                    <img src="{{ $user->profile && $user->profile->avatar
                        ? asset('storage/' . $user->profile->avatar)
                        : asset('default-avatar.png') }}"
                        alt="Profile picture" class="w-16 h-16 rounded-full object-cover border-2 border-gray-100">

                    @if (Auth::id() === $user->id)
                        <label for="avatar-upload"
                            class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full cursor-pointer hover:bg-gray-200 transition-colors border border-gray-300 group">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                            </div>
                        </label>

                        <livewire:profile-image-upload :user="$user" :profile="$profile" />
                    @endif
                </div>

                <div>
                    <h1 class="text-l font-bold">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-sm">Joined {{ $user->created_at->format('F Y') }}</p>
                </div>

            </div>

            <div class="mt-6">
                @if (Auth::id() === $user->id)
                    <livewire:profile-bio-update :profile="$user->profile ?? null" />
                @else
                    <p class="text-gray-700">{{ $user->profile->bio ?? 'No bio available.' }}</p>
                @endif
            </div>
        </div>

        <div x-data="{ activeTab: 'posts' }">

            <!--change between posts or comments-->
            <div class="flex space-x-1 mb-6">
                <button @click="activeTab = 'posts'"
                    :class="{ 'bg-gray-300': activeTab === 'posts', 'bg-white  hover:underline': activeTab !== 'posts' }"
                    class="px-3 py-2 rounded-full font-medium text-sm transition duration-200">
                    Posts
                </button>

                <button @click="activeTab = 'comments'"
                    :class="{ 'bg-gray-300': activeTab === 'comments', 'bg-white hover:underline': activeTab !== 'comments' }"
                    class="px-3 py-2 rounded-full font-medium text-sm transition duration-200">
                    Comments
                </button>
            </div>

            <!--user posts-->
            <div x-show="activeTab === 'posts'" x-cloak>
                @if ($posts->isEmpty())
                    <div class="text-center py-12 bg-white rounded-lg">
                        <p class="text-gray-500">No posts yet</p>
                    </div>
                @else
                    <div class="mb-4 flex items-center">

                        <div class="flex items-center space-x-2">


                            @if (Auth::id() === $user->id)
                                <a href="{{ route('posts.create') }}"
                                    class="flex items-center p-1.5 hover:bg-gray-200 rounded-full transition-colors duration-200 text-xs text-black border border-black">
                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M12 5a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H5a1 1 0 110-2h6V6a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Create Post') }}
                                </a>
                            @endif

                            <!--post filter-->
                            <form action="{{ route('users.show', $user) }}" method="GET" class="relative">
                                <select name="sort" onchange="this.form.submit()"
                                    class="text-xs appearance-none bg-transparent focus:outline-none focus:ring- border-none text-gray-700 pr-8 cursor-pointer rounded-lg hover:bg-gray-100 px-3 py-1 transition-colors duration-200">
                                    <option value="new" {{ request('sort', 'new') == 'new' ? 'selected' : '' }}>New
                                    </option>
                                    <option value="top" {{ request('sort') == 'top' ? 'selected' : '' }}>Top</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <ul>
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
                @endif
            </div>

            <!--user comments-->
            <div x-show="activeTab === 'comments'" x-cloak>
                @if ($user->comments->isEmpty())
                    <div class="text-center py-12 bg-white rounded-lg">
                        <p class="text-gray-500">This user has no comments.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($comments as $comment)
                            <div class="bg-white rounded-lg hover:bg-gray-50 border border-[#ddd] relative">

                                <a href="{{ route('posts.show', ['post' => $comment->commentable->id]) }}"
                                    class="absolute inset-0 z-10">
                                    <span class="sr-only">View post</span>
                                </a>


                                <div class="px-4 pt-3 pb-2 border-b text-sm text-gray-500">
                                    @if ($comment->parent_id)
                                        Replied to
                                        <a href="{{ route('users.show', ['user' => $comment->parent->user->id]) }}"
                                            class="font-medium text-black hover:underline">
                                            {{ $comment->parent->user->name }}
                                        </a>
                                    @elseif($comment->commentable_type === 'App\Models\Profile')
                                        Commented on
                                        <a href="{{ route('users.show', ['user' => $comment->commentable->user->id]) }}"
                                            class="font-medium text-black hover:underline">
                                            {{ $comment->commentable->user->name }}'s profile
                                        </a>
                                    @else
                                        Commented on
                                        <span class="font-medium text-black">{{ $comment->commentable->title }}</span>
                                    @endif
                                </div>

                                <div class=" px-3  transition-colors duration-150">
                                    @include('partials._comment', [
                                        'comment' => $comment,
                                        'post' => $comment->commentable,
                                        'hideReply' => true,
                                        'hideOptions' => true,
                                        'isUserProfile' => true,
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="comments-section" x-init="$el.classList.add('loaded')">
            @if ($profile)
                <h3 class="mt-4">Profile Comments</h3>
                <livewire:comments :commentable="$profile" wire:key="comments-{{ $profile->id }}" />
            @else
                <p>Profile must be set up to enable comments.</p>
            @endif
        </div>
    </div>



@endsection

<!--user details-->
@section('infobar')
    <div class="space-y-6">

        <div class="text-lg font-bold">
            {{ $user->name }}
        </div>

        <div class="flex justify-between">
            <div>
                <p class="font-bold">{{ $posts->count() }}</p>
                <p class="text-gray-600 text-sm">Post(s)</p>
            </div>
            <div class="mr-16">
                <p class="font-bold">{{ $user->comments->count() }}</p>
                <p class="text-gray-600 text-sm">Comments</p>
            </div>
        </div>

        <!--If the user is the profile owner-->
        @if (Auth::id() === $user->id)
            <div class="pt-4 border-t border-gray-200">
                <div class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">
                    Settings
                </div>

                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $user->profile && $user->profile->avatar
                            ? asset('storage/' . $user->profile->avatar)
                            : asset('default-avatar.png') }}"
                            alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="text-sm text-gray-600">Profile</p>
                            <p class="text-xs text-gray-500">Customize your profile</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="text-xs text-black border border-black rounded-full px-3 py-1 hover:bg-gray-100 transition-colors duration-200">
                        Edit
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
