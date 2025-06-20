<?php

namespace App\View\Components;

use App\Models\Tag;
use Illuminate\View\Component;

/**
 * Component for rendering tag badges with consistent styling and icons.
 * 
 */
class TagBadge extends Component
{
    public function __construct(
        public Tag $tag
    ) {}

    /**
     * Get Tailwind CSS classes for tag styling.
     */
    public function getColorClasses(): string
    {
        return match($this->tag->name) {
            'Announcement' => 'text-red-500 bg-red-50',
            'Help' => 'text-orange-700 bg-orange-50',
            'Discussion' => 'text-blue-500 bg-blue-50',
            default => 'text-gray-500 bg-gray-50'
        };
    }

    /**
     * Get the SVG icon markup for the tag.
     */
    public function getIconSvg(): string
    {
        return match($this->tag->name) {
            'Announcement' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" viewBox="0 0 20 20">
                <path fill="currentColor" d="M10 2L18 16H2L10 2Z"/>
                <text x="10" y="14" text-anchor="middle" fill="white" font-size="12">!</text>
            </svg>',
            'Help' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" viewBox="0 0 20 20">
                <circle fill="currentColor" cx="10" cy="10" r="8"/>
                <text x="10" y="14" text-anchor="middle" fill="white" font-size="12">?</text>
            </svg>',
            'Discussion' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" viewBox="0 0 20 20">
                <rect fill="currentColor" x="2" y="2" width="16" height="12" rx="2"/>
                <text x="10" y="10" text-anchor="middle" fill="white" font-size="12">...</text>
            </svg>',
            default => ''
        };
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('components.tag-badge');
    }
}