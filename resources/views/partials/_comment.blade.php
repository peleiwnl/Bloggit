<div class="py-2 rounded-xl">
    <div class="flex items-center space-x-2 text-[10px]">

        <!--user banner when hovering over a user -->
        <div x-data="{ tooltip: false }" class="relative">
            <x-user-tooltip :user="$comment->user">
                @if ($comment->user->id === $post->user_id)
                    <span class="text-blue-500 ml-1">OP</span>
                @endif
            </x-user-tooltip>
        </div>

        <span>•</span>
        <em class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</em>
        @if ($comment->is_edited)
            <span class="text-gray-500">(edited)</span>
        @endif
    </div>

    <p class="mt-3 text-sm px-4">{{ $comment->body }}</p>

    <div class="px-2.5">
        <div class="flex items-center space-x-2">
            @if (!isset($hideReply) && !isset($isReply))
                <button wire:click="startReply({{ $comment->id }})"
                    class="flex items-center space-x-1 text-black p-1.5 hover:bg-gray-100 rounded-full transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="text-[10px]">Reply</span>
                </button>
            @endif

            @auth
                <!--3 dots menu -->
                @if (!isset($hideOptions) && (Auth::id() === $comment->user_id || Auth::user()->isAdmin()))
                    <div x-data="{ open: false }" class="relative z-40">
                        <button @click="open = !open"
                            class="flex items-center p-1.5 hover:bg-gray-100 rounded-full transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak x-transition
                            class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg py-1 z-50">
                            <button wire:click="startEditing({{ $comment->id }})" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Edit
                            </button>
                            <button wire:click="deleteComment({{ $comment->id }})" @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Delete
                            </button>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
