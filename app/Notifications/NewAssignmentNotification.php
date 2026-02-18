<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAssignmentNotification extends Notification
{
    use Queueable;

    protected $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📚 Tugas Baru: ' . $this->assignment->title)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Ada tugas baru untuk Anda di mata kuliah **' . $this->assignment->course->name . '**')
            ->line('-Judul Tugas: ' . $this->assignment->title)
            ->line('-Deskripsi: ' . $this->assignment->description)
            ->line('-Deadline: ' . $this->assignment->deadline->format('d F Y, H:i'))
            ->action('Lihat Tugas', url('/assignments/' . $this->assignment->id))
            ->line('Jangan lupa kumpulkan tugas sebelum deadline masbro!')
            ->line('Terima kasih telah menggunakan E-Learning Kampus.')
            ->salutation('Salam, Tim E-Learning Kampus');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'assignment_id' => $this->assignment->id,
            'assignment_title' => $this->assignment->title,
            'course_id' => $this->assignment->course_id,
            'course_name' => $this->assignment->course->name,
            'deadline' => $this->assignment->deadline->toDateTimeString(),
            'type' => 'new_assignment',
            'message' => 'Tugas baru: ' . $this->assignment->title
        ];
    }
}
