<div class="mt-10 max-w-4xl ml-0 md:ml-56 mx-auto bg-white dark:bg-gray-800 rounded-lg p-6" x-data="{
    isSubmitting: false,
    showTagModal: false,
    get hasSelectedTags() {
        return $wire.selectedTags.length > 0;
    },
    clearSavedData() {
        localStorage.removeItem('postFormData');
    },
    init() {
        Livewire.on('post-submitted', () => {
            this.clearSavedData();
        });

        const savedData = JSON.parse(localStorage.getItem('postFormData') || '{}');
        if (savedData.title) {
            $wire.title = savedData.title;
            setTimeout(() => {
                $wire.formChange();
            }, 100);
        }
        if (savedData.content) {
            $wire.content = savedData.content;
        }

        this.$watch('$wire.title', (value) => {
            const savedData = JSON.parse(localStorage.getItem('postFormData') || '{}');
            localStorage.setItem('postFormData', JSON.stringify({
                ...savedData,
                title: value
            }));
        });

        this.$watch('$wire.content', (value) => {
            const savedData = JSON.parse(localStorage.getItem('postFormData') || '{}');
            localStorage.setItem('postFormData', JSON.stringify({
                ...savedData,
                content: value
            }));
        });

        window.addEventListener('clearPostFormData', () => {
            this.clearSavedData();
        });
    }
}"> <!--this whole snippet is for saving user input on refresh and asking the user if theyre sure they want to cancel,
     was much harder with livewire/js rather than just using old() from a controller-->

    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
        {{ $isEditing ? 'Edit Post' : 'Create Post' }}
    </h1>

    <form wire:submit.prevent="save" x-on:submit="isSubmitting = true" class="space-y-6">

        <div class="ml-2 flex gap-4 mb-6">
            <button type="button" wire:click="$set('selectedMode', 'text')"
                class="text-sm font-medium pb-1 {{ $selectedMode === 'text' ? 'border-b-4 border-blue-900' : 'text-gray-500 hover:text-gray-700' }}">
                Text
            </button>
            <button type="button" wire:click="$set('selectedMode', 'image')"
                class="text-sm font-medium pb-1 {{ $selectedMode === 'image' ? 'border-b-4 border-blue-900' : 'text-gray-500 hover:text-gray-700' }}">
                Image
            </button>
        </div>

        <div class="mb-4 relative">
            <div class="flex items-center gap-2">
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags->whereIn('id', $selectedTags) as $tag)
                        <x-tag-badge :tag="$tag" />
                    @endforeach
                </div>

                <button type="button" @click="showTagModal = true" x-show="hasSelectedTags" x-cloak
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="relative flex flex-col">
            <div class="relative">
                <textarea wire:model.live="title" id="title" rows="1"
                    class="peer w-full pt-4 px-3 pb-1 border {{ $this->isTitleInvalid() ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 placeholder-transparent text-sm resize-none"
                    placeholder="Title"></textarea>
                <label for="title"
                    class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 transition-all duration-200 
                        {{ strlen($title) > 0 ? 'top-1 -translate-y-0 text-xs text-blue-500' : 'top-1/2 -translate-y-1/2' }}
                        peer-focus:top-1 peer-focus:-translate-y-0 peer-focus:text-xs peer-focus:text-blue-500 
                        dark:peer-focus:text-blue-400">
                    Title
                </label>
            </div>
            <div class="flex justify-between mt-1">
                <div>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-sm {{ $this->isTitleInvalid() ? 'text-red-500' : 'text-gray-500' }}">
                    {{ $titleCharCount }}/300
                </div>
            </div>
        </div>

        <div class="flex justify-start">
            <button type="button" @click="showTagModal = true"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add tags
            </button>
            @error('selectedTags')
                <span class="text-red-500 text-sm ml-2 self-center">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            @if ($selectedMode === 'text')

                <!--text-->
                <div class="flex flex-col">
                    <textarea wire:model.live="content" id="content" rows="6"
                        class="w-full px-4 py-2 border {{ $this->isContentInvalid() ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200"
                        placeholder="Body"></textarea>
                    @error('content')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <!--images with upload & drag and drop-->
                <div class="relative border-2 border-dashed rounded-xl p-8 text-center" x-data="{ isHovering: false }"
                    @dragover.prevent="isHovering = true" @dragleave.prevent="isHovering = false"
                    @drop.prevent="
                    isHovering = false;
                    const file = $event.dataTransfer.files[0];
                    if (file) {
                        const input = document.querySelector('#image');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    }"
                    :class="{ 'border-blue-500 bg-blue-50': isHovering, 'border-gray-300': !isHovering }">

                    <input type="file" wire:model="image" id="image" class="hidden" accept="image/*">

                    <label for="image" class="flex flex-col items-center justify-center cursor-pointer h-48"
                        tabindex="0" role="button" aria-label="Upload image"
                        @keydown.enter.prevent="document.getElementById('image').click()"
                        @keydown.space.prevent="document.getElementById('image').click()">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-600">Drag and drop your image here or click to browse</p>
                    </label>


                    @if ($image)
                        <div class="mt-4">
                            <img src="{{ $image->temporaryUrl() }}" alt="Image Preview"
                                class="max-w-xs mx-auto rounded-lg shadow">
                        </div>
                    @elseif ($isEditing && $post->image_path)
                        <div class="mt-4">
                            <img src="{{ Storage::url($post->image_path) }}" alt="Current image"
                                class="max-w-xs mx-auto rounded-lg shadow">
                        </div>
                    @endif

                    @error('image')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="{{ $isEditing ? route('posts.show', $post) : route('posts.index') }}"
                @click.prevent="if (!$wire.hasChanges || confirm('Are you sure you want to discard your changes?')) { clearSavedData(); window.location.href = $el.href }"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-full focus:ring-2 focus:ring-gray-300">
                Cancel
            </a>

            <button type="submit"
                class="bg-blue-900 hover:bg-blue-950 text-white font-bold py-2 px-4 rounded-full focus:ring-2 focus:ring-blue-300">
                {{ $isEditing ? 'Update' : 'Post' }}
            </button>
        </div>

        <div x-show="showTagModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative min-h-screen flex items-center justify-center">
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full mx-4">
                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            Add tags
                        </h3>
                        <button @click="showTagModal = false"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                        @foreach ($tags as $tag)
                            <div class="flex items-center justify-between p-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"
                                tabindex="0"
                                @keydown.enter.prevent="$wire.selectedTags.includes({{ $tag->id }}) 
                                    ? $wire.selectedTags = $wire.selectedTags.filter(id => id !== {{ $tag->id }})
                                    : $wire.selectedTags.push({{ $tag->id }})"
                                role="checkbox" :aria-checked="$wire.selectedTags.includes({{ $tag->id }})">
                                <div class="flex items-center gap-2">
                                    <x-tag-badge :tag="$tag" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $tag->name }}
                                    </span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectedTags"
                                        value="{{ $tag->id }}" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end space-x-3 p-4 border-t dark:border-gray-700">
                        <button @click="showTagModal = false" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                            Cancel
                        </button>
                        <button @click="showTagModal = false" type="button" :disabled="!hasSelectedTags"
                            class="px-4 py-2 text-sm font-medium rounded-md"
                            :class="hasSelectedTags ? 'text-white bg-blue-600 hover:bg-blue-700' :
                                'text-gray-400 bg-gray-200 cursor-not-allowed'">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
