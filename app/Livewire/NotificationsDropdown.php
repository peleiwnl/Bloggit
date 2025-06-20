<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * NotificationsDropdown Livewire Component
 * 
 * Manages a dropdown notification system that displays recent notifications
 */
class NotificationsDropdown extends Component
{

    public $showDropdown = false;
    public $notifications;
    public $unreadCount = 0;

    /**
     * @var array<string, string> Event listeners for the component
     * Listens for 'refreshNotifications' event to reload notifications
     */
    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    /**
     * Initialize the component and load initial notifications
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->loadNotifications();
    }

    /**
     * Load the most recent notifications for the authenticated user
     * 
     * @return void
     */
    public function loadNotifications(): void
    {
        if (auth()->check()) {
            $this->notifications = auth()->user()
                ->notifications()
                ->latest()
                ->take(5)
                ->get();
            
            $this->unreadCount = auth()->user()
                ->unreadNotifications()
                ->count();
        }
    }

    /**
     * Mark a specific notification as read
     * 
     * @param string $notificationId The UUID of the notification to mark as read
     * @return void
     */
    public function markAsRead(string $notificationId): void
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    /**
     * Mark all unread notifications as read for the authenticated user
     * 
     * @return void
     */
    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    /**
     * Delete a specific notification
     * 
     * @param string $notificationId The UUID of the notification to delete
     * @return void
     */
    public function deleteNotification(string $notificationId): void
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    /**
     * Render the component
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.notifications-dropdown');
    }
}