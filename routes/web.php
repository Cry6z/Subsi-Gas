<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\DistributorCreate as AdminDistributorCreate;
use App\Livewire\Admin\DistributorEdit as AdminDistributorEdit;
use App\Livewire\Admin\DistributorList as AdminDistributorList;
use App\Livewire\Admin\UserList as AdminUserList;
use App\Livewire\Admin\LocationCreate as AdminLocationCreate;
use App\Livewire\Admin\LocationImport as AdminLocationImport;
use App\Livewire\Admin\LocationList as AdminLocationList;
use App\Livewire\Admin\ReportsExport as AdminReportsExport;
use App\Livewire\Distributor\DashboardOverview as DistributorDashboardOverview;
use App\Livewire\Distributor\LocationTable as DistributorLocationTable;
use App\Livewire\Distributor\LocationCreate as DistributorLocationCreate;
use App\Livewire\Distributor\LocationEdit as DistributorLocationEdit;
use App\Livewire\Public\LandingMap;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', LandingMap::class)->name('home');

Route::get('storage/{path}', function (string $path) {
    if (str_contains($path, '..')) {
        abort(404);
    }

    $disk = Storage::disk('public');
    if (! $disk->exists($path)) {
        abort(404);
    }

    return $disk->response($path);
})->where('path', '.*');

Route::get('dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin' => redirect()->to('/admin/dashboard'),
        'distributor' => redirect()->to('/distributor/dashboard'),
        default => redirect()->to('/distributor/dashboard'),
    };
})
    ->middleware(['auth', 'active'])
    ->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
});

Route::prefix('admin')
    ->middleware(['auth', 'active', 'admin'])
    ->group(function () {
        Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('distributors', AdminDistributorList::class)->name('admin.distributors');
        Route::get('distributors/create', AdminDistributorCreate::class)->name('admin.distributors.create');
        Route::get('distributors/{user}/edit', AdminDistributorEdit::class)->name('admin.distributors.edit');
        Route::get('locations', AdminLocationList::class)->name('admin.locations');
        Route::get('locations/create', AdminLocationCreate::class)->name('admin.locations.create');
        Route::get('locations/import', AdminLocationImport::class)->name('admin.locations.import');
        Route::get('reports/export', AdminReportsExport::class)->name('admin.reports.export');
    });

Route::prefix('distributor')
    ->middleware(['auth', 'active', 'role:distributor'])
    ->group(function () {
        Route::get('dashboard', DistributorDashboardOverview::class)->name('distributor.dashboard');
        Route::get('locations', DistributorLocationTable::class)->name('distributor.locations');
        Route::get('locations/create', DistributorLocationCreate::class)->name('distributor.locations.create');
        Route::get('locations/{location}/edit', DistributorLocationEdit::class)->name('distributor.locations.edit');
    });
