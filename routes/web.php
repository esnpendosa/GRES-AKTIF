<?php

use App\Http\Controllers\Api\GisApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Livewire\AssetDetailView;
use App\Livewire\DistrictDashboard;
use App\Livewire\OpportunityMap;
use App\Livewire\ProjectManagement;
use App\Livewire\PublicExplore;
use App\Livewire\RegencyDashboard;
use App\Livewire\ReportWizard;
use App\Livewire\UserProfile;
use App\Livewire\VillageDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GRES-AKTIF Platform
|--------------------------------------------------------------------------
*/

// 1. Public & Community Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', PublicExplore::class)->name('explore');
Route::get('/map', OpportunityMap::class)->name('map');
Route::get('/assets/{slug}', AssetDetailView::class)->name('assets.show');
Route::get('/report', ReportWizard::class)->name('reports.create');
Route::get('/profile', UserProfile::class)->name('profile');

// GIS GeoJSON API Endpoint
Route::get('/api/geojson', [GisApiController::class, 'geojson'])->name('api.geojson');

// 2. Authentication & Demo Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/demo/{role}', [AuthController::class, 'demoLogin'])->name('auth.demo');

// 3. Government & Command Center Dashboards
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->isVillageAdmin()) return redirect()->route('dashboard.village');
        if ($user->isDistrictAdmin()) return redirect()->route('dashboard.district');
        return redirect()->route('dashboard.regency');
    })->name('dashboard.index');

    Route::get('/village', VillageDashboard::class)->name('dashboard.village');
    Route::get('/district', DistrictDashboard::class)->name('dashboard.district');
    Route::get('/regency', RegencyDashboard::class)->name('dashboard.regency');
    Route::get('/verification', VillageDashboard::class)->name('dashboard.verification');
    Route::get('/opportunities', RegencyDashboard::class)->name('dashboard.opportunities');
    Route::get('/consensus', OpportunityMap::class)->name('dashboard.consensus');
    Route::get('/simulation', OpportunityMap::class)->name('dashboard.simulation');
    Route::get('/projects', ProjectManagement::class)->name('dashboard.projects');
    Route::get('/audit', VillageDashboard::class)->name('dashboard.audit');
});
