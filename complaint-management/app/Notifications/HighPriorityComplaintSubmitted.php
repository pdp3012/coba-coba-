<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class HighPriorityComplaintSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Complaint $complaint)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $submitterName = $this->complaint->user 
            ? $this->complaint->user->name 
            : $this->complaint->guest_name;

        return (new MailMessage)
            ->subject("🚨 High Priority Complaint #{$this->complaint->id} Submitted")
            ->greeting("Hello Admin!")
            ->line("A new high priority complaint has been submitted and requires immediate attention.")
            ->line("**Complaint Title:** {$this->complaint->title}")
            ->line("**Category:** {$this->complaint->category}")
            ->line("**Submitted by:** {$submitterName}")
            ->line("**Description:** " . Str::limit($this->complaint->description, 200))
            ->action('Review Complaint', route('admin.complaints.show', $this->complaint))
            ->line('Please review and assign this complaint as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'complaint_title' => $this->complaint->title,
            'priority' => $this->complaint->priority,
            'category' => $this->complaint->category,
        ];
    }
}
