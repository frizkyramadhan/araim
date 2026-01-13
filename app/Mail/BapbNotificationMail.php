<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BapbNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bapb;
    public $bapbRow;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bapb, $bapbRow)
    {
        $this->bapb = $bapb;
        $this->bapbRow = $bapbRow;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'BAPB Notification - ' . $this->bapb->bapb_reg;

        $mail = $this->subject($subject)
            ->view('emails.bapb_notification')
            ->with([
                'bapb' => $this->bapb,
                'bapbRow' => $this->bapbRow,
            ]);

        // Attach signed document if exists
        if ($this->bapb->signed_document && Storage::disk('public')->exists($this->bapb->signed_document)) {
            $filePath = storage_path('app/public/' . $this->bapb->signed_document);
            if (file_exists($filePath)) {
                // Get file extension and determine MIME type
                $extension = strtolower(pathinfo($this->bapb->signed_document, PATHINFO_EXTENSION));
                $mimeTypes = [
                    'pdf' => 'application/pdf',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                ];
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

                // Generate attachment filename
                $attachmentName = 'BAPB_Signed_Document_' . $this->bapb->bapb_reg . '.' . $extension;

                $mail->attach($filePath, [
                    'as' => $attachmentName,
                    'mime' => $mimeType,
                ]);
            }
        }

        return $mail;
    }
}
