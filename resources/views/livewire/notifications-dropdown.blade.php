<!-- notifications - text on mobile, bell svg on pc -->
<div class="relative" x-data="{ open: false }" @click.outside="open = false"> 
    <button @click="open = !open"
        class="block w-full m-1 sm:hidden ps-3 pe-4 py-2 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out flex items-center rounded-full">
        <span class="text-sm font-medium">Notifications</span>
        @if ($unreadCount > 0)
            <span class="ml-2 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div class="hidden sm:block">
        <button @click="open = !open"
            class="relative flex items-center p-2 hover:bg-gray-200 rounded-full transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            @if ($unreadCount > 0)
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-xs font-bold text-white bg-red-500 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>
    </div>

    <!-- notification dropdown menu-->
    <div x-show="open" x-cloak x-transition
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-60">
        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-semibold">Notifications</h3>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:text-blue-800">
                    Mark all as read
                </button>
            @endif
        </div>

        @if ($notifications && $notifications->isNotEmpty())
            <div class="max-h-64 overflow-y-auto">
                @foreach ($notifications as $notification)
                    <div wire:key="notification-{{ $notification->id }}"
                        class="relative group px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                        
                       <!-- the actual notification-->
                        <a href="{{ $notification->data['commentable_type'] === 'App\Models\Profile' 
                            ? route('users.show', $notification->data['user_id'] ?? $notification->notifiable_id)
                            : route('posts.show', $notification->data['commentable_id']) }}"
                            class="block {{ $notification->read_at ? 'opacity-50' : '' }}">
                            <div class="text-sm">
                                @if ($notification->type === 'App\Notifications\CommentNotification')
                                    @if ($notification->data['type'] === 'new')
                                        <span class="font-medium">{{ $notification->data['user_name'] }}</span>
                                        @if ($notification->data['commentable_type'] === 'App\Models\Profile')
                                            commented on your profile:
                                        @else
                                            commented on your post "<span class="font-medium">{{ $notification->data['title'] }}</span>":
                                        @endif
                                        <p class="mt-1">{{ Str::limit($notification->data['comment_body'], 100) }}</p>
                                    @elseif($notification->data['type'] === 'reply')
                                        <span class="font-medium">{{ $notification->data['user_name'] }}</span> replied
                                        to your comment on "<span class="font-medium">{{ $notification->data['title'] }}</span>":
                                        <p class="mt-1 text-gray-600">{{ Str::limit($notification->data['comment_body'], 100) }}</p>
                                        @if(isset($notification->data['original_comment']))
                                            <p class="mt-1 text-gray-500 text-xs">Original comment: {{ Str::limit($notification->data['original_comment'], 60) }}</p>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </a>
                        
                        <div class="flex justify-between items-center mt-1">
                            <div class="text-xs text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>

                            <!-- edit and delete notification buttons-->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center space-x-2">
                                @if (!$notification->read_at)
                                    <button wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-xs text-blue-600 hover:text-blue-800 bg-white px-2 py-1 rounded">
                                        Mark read
                                    </button>
                                @endif
                                <button wire:click="deleteNotification('{{ $notification->id }}')"
                                    class="text-xs text-red-600 hover:text-red-800 bg-white px-2 py-1 rounded">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-2 text-sm text-gray-500">
                No notifications
            </div>
        @endif
    </div>
</div>