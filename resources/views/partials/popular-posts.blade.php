<div class="infobar">
    <h3 class="text-[13px] font-bold mb-4">POPULAR POSTS</h3>
    <div class="space-y-4">
        <!--sorting popular posts by highest comments-->
        @foreach ($popularPosts as $post)
            <div class="flex items-center space-x-2">
                @if ($post->user->profile && $post->user->profile->avatar)
                    <img src="{{ Storage::url($post->user->profile->avatar) }}" alt="{{ $post->user->name }}'s avatar"
                        class="w-6 h-6 rounded-full object-cover">
                @else
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-xs">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <span class="text-xs text-gray-500">
                    <a href="{{ route('users.show', $post->user) }}"
                        class="text-gray-600 hover:text-gray-900 hover:underline">
                        {{ $post->user->name }}
                    </a>
                </span>
            </div>

            <div class="border-b border-gray-200 pb-3 last:border-0">
                <a href="{{ route('posts.show', $post) }}"
                    class="text-sm text-black hover:text-gray-900 hover:underline block mb-1">
                    {{ $post->title }}
                </a>
                <div class="flex items-center text-xs text-gray-500 space-x-2">
                    <span>{{ $post->comments_count }} comments</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
