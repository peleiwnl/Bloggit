<div class="flex items-center rounded-full"> <!-- small comment icon that will increase the count when a user comments on a post -->
    <div class="flex items-center space-x-1 text-black p-1.5 bg-gray-200 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="text-black text-[10px] px-1 py-0.5">
            {{ $count }}
        </span>
    </div>
</div>
