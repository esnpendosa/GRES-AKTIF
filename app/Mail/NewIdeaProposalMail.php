<?php

namespace App\Mail;

use App\Models\AssetIdea;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewIdeaProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AssetIdea $idea,
        public string $source = 'Asisten AI Kentongan'
    ) {}

    public function envelope(): Envelope
    {
        $assetName = $this->idea->asset ? $this->idea->asset->name : 'Aset Daerah';
        return new Envelope(
            subject: "[KENTONGAN AI] Gagasan Baru Warga: {$this->idea->title} ({$assetName})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-idea-proposal',
        );
    }
}
