<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    <!--3 dots menu -->
    @auth
        @if (Auth::user()->isAdmin() || Auth::user()->id === $post->user_id)
            <button @click="open = !open"
                class="absolute top-2 right-2 text-xl z-30 p-2 rounded-full hover:bg-gray-100 transition-colors focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <circle cx="7" cy="12" r="1.5" />
                    <circle cx="12" cy="12" r="1.5" />
                    <circle cx="17" cy="12" r="1.5" />
                </svg>
            </button>

            <div x-show="open" x-cloak x-transition
                class="absolute right-2 top-8 bg-white shadow-md rounded-lg p-2 z-30 border">
                <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                    class="block text-blue-600 hover:bg-gray-100 px-2 py-1 rounded">
                    Edit
                </a>
                <form method="POST" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 w-full text-left hover:bg-gray-100 px-2 py-1 rounded">
                        Delete
                    </button>
                </form>
            </div>
        @endif
    @endauth

    <div class="relative group py-2">
        @if ($isClickable ?? false)
            <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="absolute inset-0 z-10">
                <span class="sr-only">View post</span>
            </a>
        @endif

        <div
            class="py-2 {{ $isClickable ?? false ? 'px-4' : '' }} rounded-xl {{ $showHover ?? false ? 'group-hover:bg-gray-100 transition-colors duration-150' : '' }}">
            <div class="flex items-center space-x-2 text-[10px] relative">
                <x-user-tooltip :user="$post->user"
                    avatar-classes="{{ !($isClickable ?? false) ? 'w-8 h-8' : 'w-5 h-5' }}" />
                <span>•</span>
                <em class="text-gray-500">{{ $post->created_at->diffForHumans() }}</em>
                @if ($post->is_edited)
                    <span class="text-gray-500">(edited)</span>
                @endif
            </div>

            @if (!($isClickable ?? false))
                <a href="{{ url()->previous() }}"
                    class="absolute left-[-3rem] top-4 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            <div class="mt-3 mb-1 relative">
                @foreach ($post->tags as $tag)
                    <x-tag-badge :tag="$tag" class="hover:opacity-75 transition-opacity" />
                @endforeach
            </div>

            <h3 class="post-title">{{ $post->title }}</h3>

            <p class="post-content mt-3">{{ $post->content }}</p>

            @if ($post->image_path)
                <div class="mt-4">
                    <div class="aspect-w-16 aspect-h-9 relative overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($post->image_path) }}" alt="Post image associated with the post"
                            class="absolute inset-0 w-full h-full object-contain bg-gray-100">
                    </div>
                </div>
            @endif

            <div class="mt-4">
                @livewire('comment-count', ['post' => $post], key($post->id))
            </div>
        </div>
    </div>
</div>
