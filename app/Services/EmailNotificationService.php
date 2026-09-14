<?php

namespace App\Services;

use App\Mail\NewAssetReportMail;
use App\Mail\NewIdeaProposalMail;
use App\Models\AssetIdea;
use App\Models\AssetReport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Send email notification for a new asset report
     */
    public static function sendNewAssetReport(AssetReport $report, string $source = 'Asisten AI Kentongan'): bool
    {
        $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'kangdigitall@gmail.com');

        try {
            Mail::to($adminEmail)->send(new NewAssetReportMail($report, $source));

            // Also send copy to reporter if email is available and not the same
            if ($report->user && $report->user->email && $report->user->email !== $adminEmail && filter_var($report->user->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($report->user->email)->send(new NewAssetReportMail($report, $source));
            }

            Log::info("Email notification for AssetReport #{$report->id} sent to {$adminEmail}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send email notification for AssetReport #{$report->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification for a new idea proposal
     */
    public static function sendNewIdeaProposal(AssetIdea $idea, string $source = 'Asisten AI Kentongan'): bool
    {
        $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'kangdigitall@gmail.com');

        try {
            Mail::to($adminEmail)->send(new NewIdeaProposalMail($idea, $source));

            // Also send copy to proposer if email is available and not the same
            if ($idea->user && $idea->user->email && $idea->user->email !== $adminEmail && filter_var($idea->user->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($idea->user->email)->send(new NewIdeaProposalMail($idea, $source));
            }

            Log::info("Email notification for AssetIdea #{$idea->id} sent to {$adminEmail}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send email notification for AssetIdea #{$idea->id}: " . $e->getMessage());
            return false;
        }
    }
}
