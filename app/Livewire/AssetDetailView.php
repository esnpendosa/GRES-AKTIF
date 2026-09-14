<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\AssetComment;
use App\Models\AssetIdea;
use App\Models\IdeaVote;
use App\Services\Ai\AiAnalysisService;
use App\Services\AuditLogger;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssetDetailView extends Component
{
    public Asset $asset;

    // Idea Submission Form
    public string $ideaTitle = '';
    public string $ideaCategory = 'UMKM';
    public string $ideaDescription = '';
    public bool $showIdeaModal = false;

    // Comment Form
    public string $newComment = '';

    // AI Simulation Modal
    public bool $showSimulationModal = false;

    // Sorting ideas
    public string $ideasSort = 'votes'; // votes, latest, ai

    public function mount(string $slug = '')
    {
        $this->asset = Asset::with([
            'village.district',
            'category',
            'images',
            'latestAiAnalysis',
            'consensus',
            'ideas.user',
            'ideas.votes',
            'comments.user',
            'projects.updates',
        ])
        ->where('slug', $slug)
        ->orWhere('id', $slug)
        ->firstOrFail();

        if (request()->query('openIdea')) {
            $this->showIdeaModal = true;
        }
    }

    public function toggleSupport()
    {
        $this->asset->increment('supporters_count');
        $this->asset->refresh();

        $user = Auth::user();
        if ($user) {
            GamificationService::awardPoints($user, 2, 'Mendukung pemanfaatan aset ' . $this->asset->name, 'Asset', $this->asset->id);
        }
    }

    public function voteIdea(int $ideaId)
    {
        $user = Auth::user();
        if (!$user) {
            // Auto login default community user
            $user = \App\Models\User::where('email', 'masyarakat@gresaktif.id')->first();
            Auth::login($user);
        }

        $existing = IdeaVote::where('idea_id', $ideaId)->where('user_id', $user->id)->first();
        $idea = AssetIdea::findOrFail($ideaId);

        if ($existing) {
            $existing->delete();
            $idea->decrement('votes_count');
        } else {
            IdeaVote::create([
                'idea_id' => $ideaId,
                'user_id' => $user->id,
                'vote_type' => 'upvote',
            ]);
            $idea->increment('votes_count');

            // Award points
            GamificationService::awardPoints($user, 2, 'Memberikan vote ide pemanfaatan aset', 'AssetIdea', $idea->id);
        }

        // Trigger AI consensus recalculation in background
        app(AiAnalysisService::class)->updateConsensus($this->asset);

        $this->asset->refresh();
    }

    public function submitIdea()
    {
        $this->validate([
            'ideaTitle' => 'required|string|min:5|max:255',
            'ideaCategory' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            $user = \App\Models\User::where('email', 'masyarakat@gresaktif.id')->first();
            Auth::login($user);
        }

        $idea = AssetIdea::create([
            'asset_id' => $this->asset->id,
            'user_id' => $user->id,
            'title' => $this->ideaTitle,
            'category' => $this->ideaCategory,
            'description' => $this->ideaDescription,
            'votes_count' => 1,
        ]);

        // Self-vote
        IdeaVote::create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'vote_type' => 'upvote',
        ]);

        // Award +10 points
        GamificationService::awardPoints($user, 10, 'Mengajukan gagasan ide pemanfaatan aset', 'AssetIdea', $idea->id);
        AuditLogger::log('created', 'AssetIdea', $idea->id, null, $idea->toArray());

        // Update consensus
        app(AiAnalysisService::class)->updateConsensus($this->asset);

        $this->reset(['ideaTitle', 'ideaDescription', 'showIdeaModal']);
        $this->asset->refresh();
    }

    public function submitComment()
    {
        $this->validate([
            'newComment' => 'required|string|min:2|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            $user = \App\Models\User::where('email', 'masyarakat@gresaktif.id')->first();
            Auth::login($user);
        }

        AssetComment::create([
            'asset_id' => $this->asset->id,
            'user_id' => $user->id,
            'comment' => $this->newComment,
        ]);

        // Award +5 points
        GamificationService::awardPoints($user, 5, 'Berpartisipasi dalam diskusi aset', 'AssetComment', $this->asset->id);

        $this->newComment = '';
        $this->asset->refresh();
    }

    public function triggerAiAnalysis()
    {
        app(AiAnalysisService::class)->analyzeAndPersist($this->asset);
        $this->asset->refresh();
        session()->flash('success', 'Analisis AI & Pembaruan Skor Peluang Berhasil Dijalankan!');
    }

    public function render()
    {
        $ideasQuery = $this->asset->ideas()->with(['user', 'votes']);

        if ($this->ideasSort === 'latest') {
            $ideasQuery->latest();
        } elseif ($this->ideasSort === 'ai') {
            $ideasQuery->orderByDesc('is_ai_recommended')->orderByDesc('votes_count');
        } else {
            $ideasQuery->orderByDesc('votes_count');
        }

        $ideas = $ideasQuery->get();
        $comments = $this->asset->comments()->with('user')->latest()->get();

        return view('livewire.asset-detail-view', compact('ideas', 'comments'))
            ->layout('layouts.admin', ['title' => $this->asset->name]);
    }
}
