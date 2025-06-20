<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;
use App\Models\Profile;

/**
 * Notification class for handling comment-related notifications.
 */
class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;
    protected $type;

    /**
     * CommentNotification constructor.
     *
     * @param Comment $comment The comment model instance.
     * @param string $type The type of the notification, defaults to new.
     */
    public function __construct(Comment $comment, string $type = 'new')
    {
        $this->comment = $comment;
        $this->type = $type;

        $this->connection = 'database';
        $this->queue = 'default';
    }

    /**
     * Get the delivery channels for the notification.
     *
     * @param object $notifiable The notifiable entity.
     * @return array The notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the notification data for storage and broadcasting.
     *
     * @param object $notifiable The notifiable entity.
     * @return array The notification data.
     */
    protected function getNotificationData(object $notifiable): array
    {
        $commentable = $this->comment->commentable;
        $isProfile = $commentable instanceof Profile;

        $data = [
            'comment_id' => $this->comment->id,
            'commentable_id' => $isProfile ? $notifiable->id : $this->comment->commentable_id,
            'commentable_type' => get_class($commentable),
            'comment_body' => $this->comment->body,
            'user_name' => $this->comment->user->name,
            'type' => $this->type,
            'title' => $isProfile ? 'Profile Comment' : $commentable->title,
        ];

        if ($this->type === 'reply') {
            $data['original_comment'] = $this->comment->parent->body;
        }

        if ($isProfile) {
            $data['user_id'] = $commentable->user_id;
        }

        return $data;
    }

    /**
     * Get the notification data as an array for database storage.
     *
     * @param object $notifiable The notifiable entity.
     * @return array The notification data.
     */
    public function toArray(object $notifiable): array
    {
        return $this->getNotificationData($notifiable);
    }

    /**
     * Get the notification data for broadcasting
     *
     * @param object $notifiable The notifiable entity.
     * @return BroadcastMessage The broadcast message instance.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->getNotificationData($notifiable));
    }

    /**
     * Create a new comment notification instance
     *
     * @param Comment $comment The comment instance.
     * @return static A new instance of the notification for a new comment.
     */
    public static function newComment(Comment $comment): self
    {
        return new static($comment, 'new');
    }

    /**
     * Create a reply notification instance
     *
     * @param Comment $reply The reply comment instance.
     * @return static A new instance of the notification for a reply.
     */
    public static function reply(Comment $reply): self
    {
        return new static($reply, 'reply');
    }
}
