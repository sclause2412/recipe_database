<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $content = '';
    public $url;
    private $files = [];
    public $title = 'Admin Mail';

    /**
     * Create a new message instance.
     */
    public function __construct($title = null)
    {
        $this->url = url('/');
        if ($title) {
            $this->title = $title;
        }
    }

    public function addFile($path, $name)
    {
        array_push($this->files, ['path' => $path, 'name' => $name]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' - ' . $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin-mail',
            with: ['content' => $this->content, 'url' => $this->url, 'title' => $this->title]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $att = [];
        foreach ($this->files as $file) {
            array_push($att, Attachment::fromPath($file['path'])->as($file['name']));
        }
        return $att;
    }
}
