<?php

namespace App\Mail;

use App\Models\ProjectItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ProjectItem $item;

    public function __construct(ProjectItem $item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('New Assignment: ' . $this->item->title)
            ->view('emails.assignment-assigned');
    }
}
