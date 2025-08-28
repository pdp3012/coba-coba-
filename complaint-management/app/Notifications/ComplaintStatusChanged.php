<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Complaint $complaint,
        public string $oldStatus,
        public string $newStatus
    ) {
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
        $statusColor = match($this->newStatus) {
            'Pending' => '#F59E0B',
            'In Progress' => '#3B82F6',
            'Resolved' => '#10B981',
            default => '#6B7280'
        };

        $message = (new MailMessage)
            ->subject("Complaint #{$this->complaint->id} Status Updated")
            ->greeting("Hello {$notifiable->name ?? $this->complaint->guest_name}!")
            ->line("We wanted to update you on your complaint: \"{$this->complaint->title}\"")
            ->line("Status changed from **{$this->oldStatus}** to **{$this->newStatus}**.");

        if ($this->complaint->admin_notes) {
            $message->line("**Admin Notes:** {$this->complaint->admin_notes}");
        }

        if ($this->complaint->assigned_to) {
            $message->line("Your complaint has been assigned to: **{$this->complaint->assigned_to}**");
        }

        $message->action('View Complaint Details', route('complaints.show', $this->complaint))
                ->line('Thank you for using ComplaintHub!');

        return $message;
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
