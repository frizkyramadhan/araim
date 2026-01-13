<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BastNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bast;
    public $bastRow;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bast, $bastRow)
    {
        $this->bast = $bast;
        $this->bastRow = $bastRow;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'BAST Notification - ' . $this->bast->bast_reg;

        $mail = $this->subject($subject)
            ->view('emails.bast_notification')
            ->with([
                'bast' => $this->bast,
                'bastRow' => $this->bastRow,
            ]);

        // Attach signed document if exists
        if ($this->bast->signed_document && Storage::disk('public')->exists($this->bast->signed_document)) {
            $filePath = storage_path('app/public/' . $this->bast->signed_document);
            if (file_exists($filePath)) {
                // Get file extension and determine MIME type
                $extension = strtolower(pathinfo($this->bast->signed_document, PATHINFO_EXTENSION));
                $mimeTypes = [
                    'pdf' => 'application/pdf',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                ];
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

                // Generate attachment filename
                $attachmentName = 'BAST_Signed_Document_' . $this->bast->bast_reg . '.' . $extension;

                $mail->attach($filePath, [
                    'as' => $attachmentName,
                    'mime' => $mimeType,
                ]);
            }
        }

        return $mail;
    }
}
