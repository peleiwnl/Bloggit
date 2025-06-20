@props(['user'])

<!-- this will appear when a user highlights a profile -->

<div x-data="{ tooltip: false }" class="relative">
    <a href="{{ route('users.show', ['user' => $user->loadCount(['posts', 'comments'])]) }}"
        class="flex items-center space-x-2 hover:opacity-75 transition-opacity z-20 relative" @mouseover="tooltip = true"
        @mouseleave="tooltip = false">
        <img src="{{ $user->profile && $user->profile->avatar
            ? asset('storage/' . $user->profile->avatar)
            : asset('default-avatar.png') }}"
            alt="Avatar" class="w-7 h-7 rounded-full object-cover {{ $attributes->get('avatar-classes') }}">
        <span class="font-medium">
            {{ $user->name }}
            {{ $slot }}
        </span>
    </a>

    <div x-show="tooltip" x-cloak
        class="absolute left-1/2 transform -translate-x-1/2 mt-2 bg-white text-gray-800 border border-gray-200 shadow-lg z-50 p-4 w-64"
        style="border-radius: 16px;">
        <div class="flex items-center">
            <img src="{{ $user->profile && $user->profile->avatar
                ? asset('storage/' . $user->profile->avatar)
                : asset('default-avatar.png') }}"
                alt="Avatar" class="w-16 h-16 rounded-full object-cover mr-4">
            <div>
                <div class="font-medium text-base">{{ $user->name }}</div>
                <div class="mt-1 text-sm">Posts: {{ $user->posts_count }}</div>
                <div class="text-sm">Comments: {{ $user->comments_count }}</div>
            </div>
        </div>
        @if ($user->profile && $user->profile->bio)
            <div class="mt-3 text-sm text-gray-600 border-t border-gray-100 pt-3">
                {{ $user->profile->bio }}
            </div>
        @endif
    </div>
</div> 
