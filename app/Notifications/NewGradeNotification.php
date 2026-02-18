<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGradeNotification extends Notification
{
    use Queueable;

    protected $submission;
    /**
     * Create a new notification instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
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
        $gradeEmoji = $this->getGradeEmoji($this->submission->score);
        $gradeMessage = $this->getGradeMessage($this->submission->score);
        
        return (new MailMessage)
            ->subject($gradeEmoji . ' Nilai Tugas Anda Sudah Keluar!')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Tugas Anda telah dinilai oleh dosen.')
            ->line('**Mata Kuliah:** ' . $this->submission->assignment->course->name)
            ->line('**Judul Tugas:** ' . $this->submission->assignment->title)
            ->line('**Nilai Anda:** ' . $this->submission->score . ' / 100')
            ->line('**Status:** ' . $gradeMessage)
            ->action('Lihat Detail Nilai', url('/submissions/' . $this->submission->id))
            ->line('Terus semangat belajar!')
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
            'submission_id' => $this->submission->id,
            'assignment_id' => $this->submission->assignment_id,
            'assignment_title' => $this->submission->assignment->title,
            'course_id' => $this->submission->assignment->course_id,
            'course_name' => $this->submission->assignment->course->name,
            'score' => $this->submission->score,
            'type' => 'new_grade',
            'message' => 'Nilai tugas "' . $this->submission->assignment->title . '" sudah keluar: ' . $this->submission->score
        ];
    }

    /**
     * Bikin emoji berdasarkan nilai
     */

    private function getGradeEmoji($score)
    {
        if ($score >= 90) return '🌟';
        if ($score >= 80) return '👏';
        if ($score >= 70) return '👍';
        if ($score >= 60) return '📝';
        return '💪';
    }

    /**
     * Bikin pesan berdasarkan nilai
     */
    private function getGradeMessage($score)
    {
        if ($score >= 90) return 'Luar Biasa! Nilai sangat memuaskan!';
        if ($score >= 80) return 'Bagus! Pertahankan!';
        if ($score >= 70) return 'Cukup Baik, terus tingkatkan!';
        if ($score >= 60) return 'Lulus, tapi perlu ditingkatkan lagi.';
        return 'Perlu belajar lebih giat lagi.';
    }
}
