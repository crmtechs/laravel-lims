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

Route::middleware('guest')->group(function ()
{
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function ()
{
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('masters/lqms')->group(function ()
    {
        Route::get('/', MastersLqmsIndex::class)->name('masters.lqms');
        Route::get('/create', MastersLqmsCreate::class)->name('masters.lqms.create');
        Route::get('/{uuid}', MastersLqmsShow::class)->name('masters.lqms.show');
        Route::get('/{uuid}/revision/create', MastersLqmsRevisionCreate::class)->name('masters.lqms.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', MastersLqmsRevisionShow::class)->name('masters.lqms.revision.show');
        Route::get('/{uuid}/edit', MastersLqmsEdit::class)->name('masters.lqms.edit');
    });

    Route::prefix('masters/annexures')->group(function ()
    {
        Route::get('/', \App\Livewire\Masters\Annexures\Index::class)->name('masters.annexures');
        Route::get('/create', \App\Livewire\Masters\Annexures\Create::class)->name('masters.annexures.create');
        Route::get('/{uuid}', \App\Livewire\Masters\Annexures\Show::class)->name('masters.annexures.show');
        Route::get('/{uuid}/revision/create', \App\Livewire\Masters\Annexures\Revisions\Create::class)->name('masters.annexures.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', \App\Livewire\Masters\Annexures\Revisions\Show::class)->name('masters.annexures.revision.show');
        Route::get('/{uuid}/edit', \App\Livewire\Masters\Annexures\Edit::class)->name('masters.annexures.edit');
    });

    Route::prefix('masters/forms')->group(function ()
    {
        Route::get('/', \App\Livewire\Masters\Forms\Index::class)->name('masters.forms');
        Route::get('/create', \App\Livewire\Masters\Forms\Create::class)->name('masters.forms.create');
        Route::get('/{uuid}', \App\Livewire\Masters\Forms\Show::class)->name('masters.forms.show');
        Route::get('/{uuid}/revision/create', \App\Livewire\Masters\Forms\Revisions\Create::class)->name('masters.forms.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', \App\Livewire\Masters\Forms\Revisions\Show::class)->name('masters.forms.revision.show');
        Route::get('/{uuid}/edit', \App\Livewire\Masters\Forms\Edit::class)->name('masters.forms.edit');
    });

    Route::post('/logout', function ()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
