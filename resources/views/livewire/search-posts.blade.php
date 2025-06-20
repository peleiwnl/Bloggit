<div class="relative">
    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
    </div>

    <input wire:model.live="search" type="text" placeholder="Search Posts"
        class="w-full ps-10 text-sm px-3 py-2 bg-gray-50 border-0 rounded-full h-8 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none"
        style="font-family: 'IBM Plex Sans', sans-serif;" />

    <!-- if there are posts available, display them -->
    @if ($search && $posts->count() > 0)
        <div
            class="absolute z-10 w-full bg-white border rounded mt-1 shadow-lg max-h-[750px] overflow-y-auto rounded-lg">
            <ul>
                @foreach ($posts as $post)
                    <li class="px-3 py-2 hover:bg-gray-100 relative group">
                        <a href="{{ route('posts.show', $post) }}" class="absolute inset-0 z-10">
                            <span class="sr-only">View post</span>
                        </a>

                        <div class="relative">
                            <div class="post-title text-[14px]">
                                {{ $post->title }}
                            </div>

                            <div class="post-content text-[10px]">
                                {{ $post->content }}
                            </div>

                            <div class="flex items-center space-x-2 text-[10px] mt-2">
                                <a href="{{ route('users.show', ['user' => $post->user->id]) }}"
                                    class="flex items-center space-x-2 hover:opacity-75 transition-opacity z-20 relative">
                                    <img src="{{ $post->user->profile && $post->user->profile->avatar
                                        ? asset('storage/' . $post->user->profile->avatar)
                                        : asset('default-avatar.png') }}"
                                        alt="Avatar" class="w-5 h-5 rounded-full object-cover">
                                    <span class="font-medium">{{ $post->user->name }}</span>
                                </a>

                                <span>•</span>
                                <em class="text-gray-500">{{ $post->created_at->diffForHumans() }}</em>

                                @if ($post->is_edited)
                                    <span class="text-gray-500">(edited)</span>
                                @endif
                            </div>

                            <div class="mt-2 mb-1">
                                @foreach ($post->tags as $tag)
                                    <x-tag-badge :tag="$tag" class="hover:opacity-75 transition-opacity z-20 relative" />
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif($search)
        <div class="absolute z-10 w-full bg-white border rounded mt-1 shadow-lg">
            <p class="px-3 py-2 text-gray-500">No results found</p>
        </div>
    @endif
</div>
