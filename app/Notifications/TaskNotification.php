<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $project;
    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($message,$project,$task)
    {
        $this->message=$message;
        $this->project=$project;
        $this->task=$task;
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

   

    public function toDatabase($notifiable): array
    {
        return [
            'message'=>$this->message,
            'project_id'=>$this->project->id,
            'task_id'=>$this->task->id
        ];
    }
}
