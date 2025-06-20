<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Tag;

/**
 * PostForm Livewire Component
 * 
 * Handles the creation and editing of posts with support for text content,
 * images, and tags. Includes real-time validation and form state management.
 */
class PostForm extends Component
{
    use WithFileUploads;

    public $image = null;
    public $selectedMode = 'text';
    public $post;
    public $title = '';
    public $content = '';
    public $selectedTags = [];
    public $tags;
    public $isEditing = false;
    public $titleCharCount = 0;
    public $contentCharCount = 0;
    public $hasChanges = false;


    /** @var array Validation rules for the form */
    protected $rules = [
        'title' => 'required|min:1|max:300',
        'content' => 'required|min:10|max:2000',
        'selectedTags' => 'required|array|min:1',
        'image' => 'nullable|image|max:2048',
    ];

    /** @var array Custom validation messages */
    protected $messages = [
        'title.required' => 'The title is required.',
        'title.min' => 'The title must be at least 1 character.',
        'title.max' => 'The title cannot exceed 300 characters.',
        'content.required' => 'The content is required.',
        'content.min' => 'The content must be at least 10 characters.',
        'content.max' => 'The content cannot exceed 2000 characters.',
        'selectedTags.required' => 'Please select at least one tag.',
        'selectedTags.min' => 'Please select at least one tag.',
        'image.image' => 'The file must be an image.',
        'image.max' => 'The image must not be larger than 2MB.',
    ];

    /**
     * check if the title is invalid based on character count
     *
     * @return bool
     */
    public function isTitleInvalid()
    {
        return $this->titleCharCount > 0 && ($this->titleCharCount > 300 || $this->titleCharCount < 1);
    }

    /**
     * check if the content is invalid based on character count
     *
     * @return bool
     */
    public function isContentInvalid()
    {
        return $this->contentCharCount > 0 && ($this->contentCharCount > 2000 || $this->contentCharCount < 10);
    }

    /**
     * Initialize the component
     *
     * @param Post|null $post The post to edit
     * @return void
     */
    public function mount($post = null)
    {
        $this->tags = Tag::orderBy('name', 'asc')->get();
        
        if ($post) {
            $this->post = $post;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->selectedTags = $post->tags->pluck('id')->toArray();
            $this->isEditing = true;
            $this->titleCharCount = strlen($this->title);
            $this->contentCharCount = strlen($this->content);
        }

        $this->hasChanges = false;
    }

    /**
     * Mark the form as having changes
     *
     * @return void
     */
    public function formChange()
    {
        $this->hasChanges = true;
    }

    /**
     * Handle title updates
     *
     * @param string $value New title value
     * @return void
     */
    public function updatedTitle($value)
    {
        $this->titleCharCount = strlen($value);
        $this->validateOnly('title');
        $this->hasChanges = true;
    }
    
    /**
     * Handle content updates
     *
     * @param string $value New content value
     * @return void
     */
    public function updatedContent($value)
    {
        $this->contentCharCount = strlen($value);
        $this->validateOnly('content');
        $this->hasChanges = true;
    }
    
    /**
     * Handle tag selection updates
     *
     * @return void
     */
    public function updatedSelectedTags()
    {
        $this->hasChanges = true;
    }

    /**
     * Save the post
     *
     * Validates the form data and either creates a new post or updates
     * an existing one, including handling image uploads and tag relationships.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        $validatedData = $this->validate();
        
        if ($this->isEditing) {
            if ($this->image && $this->post->image_path) {
                Storage::disk('public')->delete($this->post->image_path);
            }
            
            $imagePath = $this->imageUpload();
            
            $this->post->update([
                'title' => $this->title,
                'content' => $this->content,
                'image_path' => $imagePath ?? $this->post->image_path,
                'is_edited' => true
            ]);
            $this->post->tags()->sync($this->selectedTags);

        } else {

            $imagePath = $this->imageUpload();
            
            $post = Post::create([
                'title' => $this->title,
                'content' => $this->content,
                'image_path' => $imagePath,
                'user_id' => auth()->id(),
            ]);
            $post->tags()->sync($this->selectedTags);
        }
            
        $this->hasChanges = false;
        $this->dispatch('post-submitted');
        
        return $this->isEditing 
            ? redirect()->route('posts.show', $this->post)
            : redirect()->route('posts.index');
    }

    /**
     * Handle image file upload
     *
     * @return string|null The path to the stored image or null if no image
     */
    private function imageUpload()
    {
        if ($this->image) {
            return $this->image->store('post-images', 'public');
        }
        return null;
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.post-form');
    }

    /**
     * Handle image upload from drag-and-drop or file input
     *
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @return void
     */
    public function uploadImage($file)
    {
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $this->image = $file;
        }
        $this->hasChanges = true;
    }
}