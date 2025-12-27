<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicShareController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;


Route::get('/s/{token}', [PublicShareController::class, 'show'])->name('share.show');
Route::get('/s/{token}/download', [PublicShareController::class, 'download'])->name('share.download');
Route::get('/s/{token}/download-folder', [PublicShareController::class, 'downloadFolder'])->name('share.downloadFolder');

Route::get('/file/view/{file}', [App\Http\Controllers\FileController::class, 'preview'])
    ->middleware(['auth'])
    ->name('file.view');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::post('/upload-chunk', [FileController::class, 'uploadChunk'])->name('upload.chunk');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
});

require __DIR__.'/auth.php';
