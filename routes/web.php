<?php

use App\Http\Controllers\Api\GisApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Livewire\AssetDetailView;
use App\Livewire\AssetVerification;
use App\Livewire\DistrictDashboard;
use App\Livewire\OpportunityMap;
use App\Livewire\ProjectManagement;
use App\Livewire\PublicExplore;
use App\Livewire\RegencyDashboard;
use App\Livewire\ReportWizard;
use App\Livewire\UserProfile;
use App\Livewire\SipadesKibAReport;
use App\Livewire\SipadesKibReport;
use App\Livewire\SipadesLhiReport;
use App\Livewire\SipadesRekapReport;
use App\Livewire\SipadesReportHub;
use App\Livewire\VillageReportIndex;
use App\Livewire\VillageDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KENTONGAN AI Platform
|--------------------------------------------------------------------------
*/

// 1. Public & Community Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', PublicExplore::class)->name('explore');
Route::get('/map', OpportunityMap::class)->name('map');
Route::get('/laporan-desa', VillageReportIndex::class)->name('reports.villages');
Route::get('/laporan-desa/kib-a/{villageId?}', SipadesKibAReport::class)->name('reports.kib_a');
Route::get('/assets/{slug}', AssetDetailView::class)->name('assets.show');
Route::get('/report', ReportWizard::class)->name('reports.create');
Route::get('/profile', UserProfile::class)->name('profile');

// -----------------------------------------------------------------------
// SIPADES R3 Report Routes
// -----------------------------------------------------------------------
Route::get('/laporan-sipades', SipadesReportHub::class)->name('reports.sipades');

// KIB B-F & KIR
Route::get('/laporan-sipades/kib-b/{villageId?}', SipadesKibReport::class)->defaults('kib', 'b')->name('reports.sipades.kib-b');
Route::get('/laporan-sipades/kib-c/{villageId?}', SipadesKibReport::class)->defaults('kib', 'c')->name('reports.sipades.kib-c');
Route::get('/laporan-sipades/kib-d/{villageId?}', SipadesKibReport::class)->defaults('kib', 'd')->name('reports.sipades.kib-d');
Route::get('/laporan-sipades/kib-e/{villageId?}', SipadesKibReport::class)->defaults('kib', 'e')->name('reports.sipades.kib-e');
Route::get('/laporan-sipades/kib-f/{villageId?}', SipadesKibReport::class)->defaults('kib', 'f')->name('reports.sipades.kib-f');
Route::get('/laporan-sipades/kir/{villageId?}',   SipadesKibReport::class)->defaults('kib', 'kir')->name('reports.sipades.kir');

// LHI routes
Route::get('/laporan-sipades/lhi-tanah/{villageId?}',     SipadesLhiReport::class)->defaults('lhi', 'tanah')->name('reports.sipades.lhi-tanah');
Route::get('/laporan-sipades/lhi-mesin/{villageId?}',     SipadesLhiReport::class)->defaults('lhi', 'mesin')->name('reports.sipades.lhi-mesin');
Route::get('/laporan-sipades/lhi-kendaraan/{villageId?}', SipadesLhiReport::class)->defaults('lhi', 'kendaraan')->name('reports.sipades.lhi-kendaraan');
Route::get('/laporan-sipades/lhi-gedung/{villageId?}',    SipadesLhiReport::class)->defaults('lhi', 'gedung')->name('reports.sipades.lhi-gedung');
Route::get('/laporan-sipades/lhi-jalan/{villageId?}',     SipadesLhiReport::class)->defaults('lhi', 'jalan')->name('reports.sipades.lhi-jalan');
Route::get('/laporan-sipades/lhi-lainnya/{villageId?}',   SipadesLhiReport::class)->defaults('lhi', 'lainnya')->name('reports.sipades.lhi-lainnya');

// Rekap routes
Route::get('/laporan-sipades/pengadaan-rekap/{villageId?}',   SipadesRekapReport::class)->defaults('rekap', 'pengadaan-rekap')->name('reports.sipades.pengadaan-rekap');
Route::get('/laporan-sipades/pengadaan-dana/{villageId?}',    SipadesRekapReport::class)->defaults('rekap', 'pengadaan-dana')->name('reports.sipades.pengadaan-dana');
Route::get('/laporan-sipades/pemanfaatan/{villageId?}',       SipadesRekapReport::class)->defaults('rekap', 'pemanfaatan')->name('reports.sipades.pemanfaatan');
Route::get('/laporan-sipades/penghapusan/{villageId?}',       SipadesRekapReport::class)->defaults('rekap', 'penghapusan')->name('reports.sipades.penghapusan');
Route::get('/laporan-sipades/penghapusan-rinci/{villageId?}', SipadesRekapReport::class)->defaults('rekap', 'penghapusan-rinci')->name('reports.sipades.penghapusan-rinci');

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
    Route::get('/verification', AssetVerification::class)->name('dashboard.verification');
    Route::get('/opportunities', RegencyDashboard::class)->name('dashboard.opportunities');
    Route::get('/consensus', OpportunityMap::class)->name('dashboard.consensus');
    Route::get('/simulation', OpportunityMap::class)->name('dashboard.simulation');
    Route::get('/projects', ProjectManagement::class)->name('dashboard.projects');
    Route::get('/audit', VillageDashboard::class)->name('dashboard.audit');
    Route::get('/boundary', \App\Livewire\VillageBoundaryEditor::class)->name('dashboard.boundary');
});
