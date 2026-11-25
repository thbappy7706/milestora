<?php

use App\Http\Controllers\RoadmapController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::resource('roadmaps', RoadmapController::class)->only(['index', 'create', 'store', 'show']);
});

require __DIR__.'/settings.php';
