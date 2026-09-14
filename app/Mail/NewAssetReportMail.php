<?php

namespace App\Mail;

use App\Models\AssetReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAssetReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AssetReport $report,
        public string $source = 'Asisten AI Kentongan'
    ) {}

    public function envelope(): Envelope
    {
        $villageName = $this->report->village ? $this->report->village->name : 'Gresik';
        return new Envelope(
            subject: "[KENTONGAN AI] Laporan Aset Baru: {$this->report->title} ({$villageName})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-asset-report',
        );
    }
}
