<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Masters\Lqms\Index as MastersLqmsIndex;
use App\Livewire\Masters\Lqms\Create as MastersLqmsCreate;
use App\Livewire\Masters\Lqms\Show as MastersLqmsShow;
use App\Livewire\Masters\Lqms\Revisions\Create as MastersLqmsRevisionCreate;
use App\Livewire\Masters\Lqms\Edit as MastersLqmsEdit;
use App\Livewire\Masters\Lqms\Revisions\Show as MastersLqmsRevisionShow;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('masters/lqms')->group(function () {
        Route::get('/', MastersLqmsIndex::class)->name('masters.lqms');
        Route::get('/create', MastersLqmsCreate::class)->name('masters.lqms.create');
        Route::get('/{uuid}', MastersLqmsShow::class)->name('masters.lqms.show');
        Route::get('/{uuid}/revision/create', MastersLqmsRevisionCreate::class)->name('masters.lqms.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', MastersLqmsRevisionShow::class)->name('masters.lqms.revision.show');
        Route::get('/{uuid}/edit', MastersLqmsEdit::class)->name('masters.lqms.edit');
    });

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
