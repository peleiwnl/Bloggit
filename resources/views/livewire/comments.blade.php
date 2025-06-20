<div>
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('error') }}
        </div>
    @endif

    @auth
        <div class="mb-4">
            <form wire:submit.prevent="storeComment">

                <!-- text area modifiers -->
                <div x-data="{
                
                    expandTextArea: false, 
                    init() {
                        $wire.on('commentAdded', () => {
                            this.expandTextArea = false;
                        });
                        $wire.on('commentReset', () => {
                            this.expandTextArea = false;
                        });
                    }
                }" class="relative">

                    <textarea x-ref="commentTextarea" wire:model.live="body" x-on:focus="expandTextArea = true"
                        class="w-full px-3 py-2 mt-2 rounded-2xl resize-none focus:outline-none focus:ring-0 focus:border-gray-300 border border-gray-300 transition-all duration-200 ease-in-out placeholder:text-xs text-sm leading-tight"
                        :class="{ 'pb-12': expandTextArea || $wire.body }" :rows="expandTextArea || $wire.body ? '3' : '1'"
                        placeholder="Add your reply"></textarea>

                    <div x-cloak x-show="expandTextArea || $wire.body" class="absolute bottom-3 right-3 flex space-x-2">
                        <button type="button" @click="expandTextArea = false; $wire.body = ''"
                            class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-3 py-1 text-sm bg-black text-white rounded hover:bg-black disabled:opacity-50"
                            :disabled="!$wire.body?.trim()">
                            Comment

                        </button>
                    </div>

                </div>
                @error('body')
                    <span class="text-red-500 text-sm px-4">{{ $message }}</span>
                @enderror
            </form>
        </div>
    @else
        <div class="text-sm mb-8 text-center p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p>Please <a href="{{ route('login') }}" class="text-blue-500 hover:underline">log in</a> to leave a comment.
            </p>
        </div>
    @endauth

    <!-- comments -->
    @foreach ($comments as $comment)
        <div class="comment-container mb-4">
            @if ($editingCommentId === $comment->id)
                <form wire:submit.prevent="updateComment">
                    <textarea wire:model="editingCommentBody"
                        class="w-full px-3 py-2 rounded-2xl resize-none focus:outline-none focus:ring-0 focus:border-gray-300 border border-gray-300"
                        rows="3"></textarea>
                    @error('editingCommentBody')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                    <div class="mt-2">
                        <button type="submit" class="px-3 py-1 text-sm bg-black text-white rounded">Update</button>
                        <button type="button" wire:click="cancelEditing"
                            class="px-3 py-1 text-sm text-gray-500 ml-2">Cancel</button>
                    </div>
                </form>
            @else
                @include('partials._comment', ['comment' => $comment])

                <!-- replying -->
                @if ($replyingTo === $comment->id)
                    <div class="mt-2 ml-8">
                        <form wire:submit.prevent="storeReply">
                            <div class="relative" x-data="{ hasContent: false }">
                                <textarea wire:model.live="replyBody" x-on:input="hasContent = $el.value.trim().length > 0"
                                    class="w-full px-3 py-2 rounded-2xl resize-none focus:outline-none focus:ring-0 focus:border-gray-300 border border-gray-300 text-sm"
                                    rows="3" placeholder="Write your reply..."></textarea>
                                <div class="absolute bottom-3 right-3 flex space-x-2">
                                    <button type="button" wire:click="cancelReply"
                                        class="px-3 py-1 text-sm text-gray-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-3 py-1 text-sm rounded transition-colors"
                                        :class="hasContent ? 'bg-black text-white hover:bg-black' :
                                            'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                        :disabled="!hasContent">
                                        Reply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- comment replies -->
                @if ($comment->replies->count() > 0)
                    <div class="ml-8 space-y-4">
                        @foreach ($comment->replies as $reply)
                            <div class="p-1 border-l-2 border-gray-200">
                                @if ($editingCommentId === $reply->id)
                                    <form wire:submit.prevent="updateComment">
                                        <textarea wire:model="editingCommentBody" class="w-full p-2 border rounded" rows="3"></textarea>
                                        @error('editingCommentBody')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                        <div class="mt-2">
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Update
                                            </button>
                                            <button type="button" wire:click="cancelEditing"
                                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    @include('partials._comment', ['comment' => $reply, 'isReply' => true])
                                @endif

                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    @endforeach
</div>
