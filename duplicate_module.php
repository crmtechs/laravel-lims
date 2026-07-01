<?php

$baseDir = __DIR__;

// Maps of files to copy: Source => Destination
$files = [
    // Livewire classes
    'app/Livewire/Masters/Lqms/Index.php' => 'app/Livewire/Masters/Annexures/Index.php',
    'app/Livewire/Masters/Lqms/Show.php' => 'app/Livewire/Masters/Annexures/Show.php',
    'app/Livewire/Masters/Lqms/Edit.php' => 'app/Livewire/Masters/Annexures/Edit.php',
    'app/Livewire/Masters/Lqms/Create.php' => 'app/Livewire/Masters/Annexures/Create.php',
    'app/Livewire/Masters/Lqms/Revisions/Show.php' => 'app/Livewire/Masters/Annexures/Revisions/Show.php',
    'app/Livewire/Masters/Lqms/Revisions/Create.php' => 'app/Livewire/Masters/Annexures/Revisions/Create.php',

    // Requests
    'app/Http/Requests/LQMStoreRequest.php' => 'app/Http/Requests/AnnexureStoreRequest.php',
    'app/Http/Requests/LQMUpdateRequest.php' => 'app/Http/Requests/AnnexureUpdateRequest.php',
    'app/Http/Requests/LQMRevisionCreateRequest.php' => 'app/Http/Requests/AnnexureRevisionCreateRequest.php',

    // Models
    'app/Models/LQMs_Master.php' => 'app/Models/Annexures_Master.php',
    'app/Models/LQMs_Masters_Revision.php' => 'app/Models/Annexures_Masters_Revision.php',

    // Views
    'resources/views/livewire/masters/lqms/index.blade.php' => 'resources/views/livewire/masters/annexures/index.blade.php',
    'resources/views/livewire/masters/lqms/form.blade.php' => 'resources/views/livewire/masters/annexures/form.blade.php',
    'resources/views/livewire/masters/lqms/show.blade.php' => 'resources/views/livewire/masters/annexures/show.blade.php',
    'resources/views/livewire/masters/lqms/revisions/create.blade.php' => 'resources/views/livewire/masters/annexures/revisions/create.blade.php',
    'resources/views/livewire/masters/lqms/revisions/show.blade.php' => 'resources/views/livewire/masters/annexures/revisions/show.blade.php',

    // JS
    'public/js/masters/lqms/index.js' => 'public/js/masters/annexures/index.js',
    'public/js/masters/lqms/form.js' => 'public/js/masters/annexures/form.js',
    'public/js/masters/lqms/show.js' => 'public/js/masters/annexures/show.js',
];

$replacements = [
    'LQMs_Masters_Revision' => 'Annexures_Masters_Revision',
    'LQMs_Master' => 'Annexures_Master',
    'LQMStoreRequest' => 'AnnexureStoreRequest',
    'LQMUpdateRequest' => 'AnnexureUpdateRequest',
    'LQMRevisionCreateRequest' => 'AnnexureRevisionCreateRequest',
    'lqms_masters_revisions' => 'annexures_masters_revisions',
    'lqms_masters_revision' => 'annexures_masters_revision',
    'lqms_masters' => 'annexures_masters',
    'lqms_master' => 'annexures_master',
    'LQMs' => 'Annexures',
    'Lqms' => 'Annexures',
    'lqms' => 'annexures',
    'LQM' => 'Annexure',
    'Lqm' => 'Annexure',
    'lqm' => 'annexure',
    // route names and URLs
    'masters.lqms' => 'masters.annexures',
    'masters/lqms' => 'masters/annexures',
];

// Migrations
$migrations = glob($baseDir . '/database/migrations/*lqms_masters*');
foreach ($migrations as $index => $migration) {
    $basename = basename($migration);
    // change date to current time + $index seconds
    $newDate = date('Y_m_d_His', time() + $index);
    $newBasename = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', $newDate . '_', $basename);
    $newBasename = str_replace('lqms_masters', 'annexures_masters', $newBasename);
    $files[str_replace($baseDir . '/', '', $migration)] = 'database/migrations/' . $newBasename;
}

foreach ($files as $source => $dest) {
    $sourcePath = $baseDir . '/' . $source;
    $destPath = $baseDir . '/' . $dest;

    if (!file_exists($sourcePath)) {
        echo "Source not found: $sourcePath\n";
        continue;
    }

    $content = file_get_contents($sourcePath);

    // Apply replacements
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }

    // Ensure directory exists
    $dir = dirname($destPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    // Write file using standard allmanstyle formatting if php
    // Wait, standard formatting is just leaving it as is since we are duplicating
    file_put_contents($destPath, $content);
    echo "Created: $dest\n";
}

// Now handle routes/web.php
$routesFile = $baseDir . '/routes/web.php';
$routesContent = file_get_contents($routesFile);
if (strpos($routesContent, 'masters.annexures') === false) {
    // Find where lqms routes are and add annexures routes below them
    $lqmsRoutesBlock = "Route::prefix('masters/lqms')->group(function () {
        Route::get('/', MastersLqmsIndex::class)->name('masters.lqms');
        Route::get('/create', MastersLqmsCreate::class)->name('masters.lqms.create');
        Route::get('/{uuid}', MastersLqmsShow::class)->name('masters.lqms.show');
        Route::get('/{uuid}/revision/create', MastersLqmsRevisionCreate::class)->name('masters.lqms.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', MastersLqmsRevisionShow::class)->name('masters.lqms.revision.show');
        Route::get('/{uuid}/edit', MastersLqmsEdit::class)->name('masters.lqms.edit');
    });";

    $annexuresRoutesBlock = "Route::prefix('masters/annexures')->group(function () {
        Route::get('/', \\App\\Livewire\\Masters\\Annexures\\Index::class)->name('masters.annexures');
        Route::get('/create', \\App\\Livewire\\Masters\\Annexures\\Create::class)->name('masters.annexures.create');
        Route::get('/{uuid}', \\App\\Livewire\\Masters\\Annexures\\Show::class)->name('masters.annexures.show');
        Route::get('/{uuid}/revision/create', \\App\\Livewire\\Masters\\Annexures\\Revisions\\Create::class)->name('masters.annexures.revision.create');
        Route::get('/{uuid}/revision/{revisionUuid}', \\App\\Livewire\\Masters\\Annexures\\Revisions\\Show::class)->name('masters.annexures.revision.show');
        Route::get('/{uuid}/edit', \\App\\Livewire\\Masters\\Annexures\\Edit::class)->name('masters.annexures.edit');
    });";

    // Just append it after the lqms routes group
    $routesContent = preg_replace(
        "/(Route::prefix\('masters\/lqms'\)->group\(function \(\) \{.*?\}\);)/s",
        "$1\n\n    $annexuresRoutesBlock",
        $routesContent
    );
    file_put_contents($routesFile, $routesContent);
    echo "Updated routes/web.php\n";
}

echo "Done!\n";
