<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    use Queueable;

    protected $message;
    protected $project;

    /**
     * Create a new notification instance.
     */
    public function __construct($message,$project)
    {
        $this->message=$message;
        $this->project=$project;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

   

    public function toDatabase($notifiable): array{
        return [
            'message'=>$this->message,
            'project_id'=>$this->project->id
        ];
    }
}
