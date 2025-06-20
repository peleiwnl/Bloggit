<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if ($isEditing)
        <div class="space-y-4">
            <textarea wire:model="bio" class="w-full p-2 border rounded-lg" rows="4"></textarea>

            @error('bio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div class="flex space-x-2">
                <button wire:click="updateBio" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                <button wire:click="toggleEdit" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </div>
    @else
        <div class="space-y-2">
            <strong>About </strong>

            @if (auth()->id() === $profile->user_id)
                <p class="text-sm text-gray-700 cursor-pointer" wire:click="toggleEdit">
                    {{ $profile->bio }}
                </p>
            @else
                <p class="text-sm text-gray-700">{{ $profile->bio }}</p>
            @endif
        </div>
    @endif
</div>
