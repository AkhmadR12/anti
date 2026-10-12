<?php

use App\Http\Controllers\CoupleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\PublicRsvpController;
use App\Http\Controllers\RsvpManagementController;
use App\Http\Controllers\TemplateCatalogController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingEventController;
use App\Http\Controllers\WeddingSettingController;
use App\Http\Controllers\WeddingWizardController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    $templates = \Illuminate\Support\Facades\Schema::hasTable('templates')
        ? \App\Models\Template::where('is_active', true)->get()
        : collect();

    return view('welcome', compact('templates'));
})->name('home');

// Public Template Showcase & Demos
Route::get('/templates', [TemplateCatalogController::class, 'index'])->name('templates.index');
Route::get('/templates/{slug}/demo', [TemplateCatalogController::class, 'demo'])->name('templates.demo');

// Public Invitations (General & Personalized)
Route::get('/invitation/{slug}', [PublicInvitationController::class, 'show'])->name('invitation.show');
Route::get('/invitation/{slug}/{guestSlug}', [PublicInvitationController::class, 'showPersonalized'])->name('invitation.guest');

// Public RSVP Submission (Rate limited: 10 per minute per IP to prevent spam)
Route::post('/invitation/{slug}/rsvp', [PublicRsvpController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('invitation.rsvp');

// Authenticated SaaS Dashboard & Wedding Management
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Wedding Creation Wizard
    Route::get('/weddings/create', [WeddingWizardController::class, 'create'])->name('weddings.create');
    Route::post('/weddings', [WeddingWizardController::class, 'store'])->name('weddings.store');

    // Template Selector for Authenticated User
    Route::post('/templates/{template}/select', [TemplateCatalogController::class, 'select'])->name('templates.select');

    // Wedding Core Management
    Route::prefix('weddings/{wedding}')->name('weddings.')->group(function () {
        Route::get('/edit', [WeddingController::class, 'edit'])->name('edit');
        Route::put('/', [WeddingController::class, 'update'])->name('update');
        Route::delete('/', [WeddingController::class, 'destroy'])->name('destroy');
        Route::post('/publish', [WeddingController::class, 'publish'])->name('publish');
        Route::get('/preview', [WeddingController::class, 'preview'])->name('preview');

        // Couple Management
        Route::post('/couple', [CoupleController::class, 'update'])->name('couple.update');

        // Events Management
        Route::post('/events', [WeddingEventController::class, 'store'])->name('events.store');
        Route::put('/events/{event}', [WeddingEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [WeddingEventController::class, 'destroy'])->name('events.destroy');

        // Gallery Management
        Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
        Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::post('/galleries/reorder', [GalleryController::class, 'reorder'])->name('galleries.reorder');

        // Settings Management
        Route::put('/settings', [WeddingSettingController::class, 'update'])->name('settings.update');

        // Guest Management
        Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
        Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
        Route::put('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
        Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
        Route::post('/guests/{guest}/mark-sent', [GuestController::class, 'markSent'])->name('guests.mark-sent');
        Route::post('/guests/import', [GuestController::class, 'import'])->name('guests.import');

        // RSVP & Guestbook Management
        Route::get('/rsvps', [RsvpManagementController::class, 'index'])->name('rsvps.index');
        Route::delete('/rsvps/{rsvp}', [RsvpManagementController::class, 'destroy'])->name('rsvps.destroy');
        Route::get('/rsvps/export', [RsvpManagementController::class, 'export'])->name('rsvps.export');
    });

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
