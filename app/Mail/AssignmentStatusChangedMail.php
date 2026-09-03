<?php

namespace App\Mail;

use App\Models\ProjectItem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ProjectItem $item;
    public string $oldStatus;
    public string $newStatus;
    public User $changedBy;

    public function __construct(ProjectItem $item, string $oldStatus, string $newStatus, User $changedBy)
    {
        $this->item = $item;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
    }

    public function build()
    {
        return $this->subject('Status Updated: ' . $this->item->title)
            ->view('emails.assignment-status-changed');
    }
}
