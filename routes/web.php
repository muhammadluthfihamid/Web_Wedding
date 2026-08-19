<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    DashboardController,
    HomeController,
    InfoController,
    StoryController,
    GalleryController,
    RsvpController,
    GiftsController,
    BiodataPriaController,
    BiodataWanitaController,
    ProfileController,
    WishController,
    NotificationController,
    RentalController,
    AudioController,
};

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [RentalController::class, 'index'])->name('rental.index');
Route::get('/sewa', fn () => redirect()->route('rental.index'));

Route::get('/demo', [DashboardController::class, 'index'])->name('dashboard.demo');
Route::get('/dashboard', fn (Illuminate\Http\Request $request) => redirect()->route('dashboard.demo', $request->query()))->name('dashboard');

Route::post('/wish', [WishController::class, 'storePublic'])->middleware('throttle:5,1')->name('wish.storePublic');

// Undangan publik per penyewa
Route::get('/undangan/{slug}', [DashboardController::class, 'undangan'])->name('undangan.show');

// Helper route to serve storage files on shared hosting / InfinityFree (secured against path traversal)
Route::get('/storage/{path}', function ($path) {
    $basePath = realpath(storage_path('app/public'));
    $targetPath = storage_path('app/public/' . $path);
    $filePath = realpath($targetPath);

    if (!$filePath || !$basePath || !str_starts_with($filePath, $basePath) || !file_exists($filePath)) {
        abort(404);
    }
    $mimeType = mime_content_type($filePath) ?: 'image/png';
    return response()->file($filePath, ['Content-Type' => $mimeType]);
})->where('path', '.*');

Route::middleware('auth')->group(function () {
    Route::get('/sewa/status',          [RentalController::class, 'status'])->name('rental.status');
    Route::get('/sewa/pesan/{package}', [RentalController::class, 'orderForm'])->name('rental.orderForm');
    Route::post('/sewa/pesan',          [RentalController::class, 'order'])->name('rental.order');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Auth::routes([
    'register' => true,
]);

Route::get('/rental-info', function () {
    $user = Auth::user();
    if ($user && ($user->isAdmin() || $user->isSuperAdmin() || $user->hasActiveRental())) {
        return redirect()->route('admin.home');
    }
    return redirect()->route('rental.status');
})->middleware('auth')->name('rental.info');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Redirect /admin → login (no name needed; nothing references this route by name)
    Route::get('/', function () {

    Route::middleware(['auth', 'role:superadmin,admin'])->group(function () {

        Route::get('/home', [HomeController::class, 'index'])->name('admin.home');

        Route::resource('info',          InfoController::class);
        Route::get('/audio',             [AudioController::class, 'index'])->name('audio.index');
        Route::post('/audio',            [AudioController::class, 'update'])->name('audio.update');
        Route::post('/audio/preset',     [AudioController::class, 'storePreset'])->name('audio.storePreset')->middleware('role:superadmin');
        Route::delete('/audio/preset',   [AudioController::class, 'destroyPreset'])->name('audio.destroyPreset')->middleware('role:superadmin');
        Route::resource('story',         StoryController::class);
        Route::resource('gallery',       GalleryController::class);
        Route::get('guests/export-csv',  [\App\Http\Controllers\Admin\GuestController::class, 'exportCsv'])->name('guests.exportCsv');
        Route::get('guests/template-csv', [\App\Http\Controllers\Admin\GuestController::class, 'downloadTemplate'])->name('guests.downloadTemplate');
        Route::post('guests/import-csv',  [\App\Http\Controllers\Admin\GuestController::class, 'importCsv'])->name('guests.importCsv');
        Route::post('guests/{guest}/toggle-sent', [\App\Http\Controllers\Admin\GuestController::class, 'toggleSent'])->name('guests.toggleSent');
        Route::resource('guests',        \App\Http\Controllers\Admin\GuestController::class);
        Route::resource('gifts',         GiftsController::class);
        Route::resource('users',         \App\Http\Controllers\UserController::class)->middleware('role:superadmin');
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\UserController::class, 'resetPassword'])
            ->name('users.resetPassword')->middleware('role:superadmin');

        Route::resource('biodata-pria',   BiodataPriaController::class)->names('biodataPria')->parameters(['biodata-pria' => 'biodataPria']);
        Route::resource('biodata-wanita', BiodataWanitaController::class)->names('biodataWanita')->parameters(['biodata-wanita' => 'biodataWanita']);
        Route::resource('turut-mengundang', \App\Http\Controllers\TurutMengundangController::class)->names('turutMengundang')->parameters(['turut-mengundang' => 'turutMengundang']);

        Route::get('rsvp/export-csv',    [RsvpController::class, 'exportCsv'])->name('rsvp.exportCsv');
        Route::resource('rsvp',          RsvpController::class);

        Route::get('wish/export-csv',    [WishController::class, 'exportCsv'])->name('wish.exportCsv');
        Route::resource('wish', WishController::class)->only(['index', 'destroy']);

        Route::get('/profile',  [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');

        // Notification mark-as-read (AJAX)
        Route::post('/notifications/mark-rsvp-read',   [NotificationController::class, 'markRsvpRead'])->name('notifications.markRsvpRead');
        Route::post('/notifications/mark-wish-read',   [NotificationController::class, 'markWishRead'])->name('notifications.markWishRead');
        Route::post('/notifications/mark-orders-read', [NotificationController::class, 'markOrdersRead'])->name('notifications.markOrdersRead');

        // Switch Event Type Mode (Pernikahan vs Khitanan)
        Route::post('/switch-event-type', [\App\Http\Controllers\Admin\SettingController::class, 'switchEventType'])->name('admin.switchEventType');

        // ── RENTAL MANAGEMENT & STORE SETTINGS (superadmin only) ──
        Route::middleware('role:superadmin')->group(function () {
            // Settings
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
            Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

            // Rental Management
            Route::prefix('rental')->name('admin.rental.')->group(function () {
                // Orders
                Route::get('orders',                   [\App\Http\Controllers\Admin\RentalManageController::class, 'index'])->name('orders.index');
                Route::get('orders/{order}',           [\App\Http\Controllers\Admin\RentalManageController::class, 'show'])->name('orders.show');
                Route::post('orders/{order}/approve',    [\App\Http\Controllers\Admin\RentalManageController::class, 'approve'])->name('orders.approve');
                Route::post('orders/{order}/reject',     [\App\Http\Controllers\Admin\RentalManageController::class, 'reject'])->name('orders.reject');
                Route::post('orders/{order}/deactivate', [\App\Http\Controllers\Admin\RentalManageController::class, 'deactivate'])->name('orders.deactivate');

                // Packages CRUD
                Route::resource('packages', \App\Http\Controllers\Admin\RentalPackageController::class)
                    ->names('packages');
            });

            // Themes CRUD (Kelola & Review Tema)
            Route::post('themes/{theme}/toggle-status', [\App\Http\Controllers\Admin\ThemeController::class, 'toggleStatus'])->name('admin.themes.toggleStatus');
            Route::resource('themes', \App\Http\Controllers\Admin\ThemeController::class)->names('admin.themes');
        });
    });
});
