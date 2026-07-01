<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\LQMs_Master;
use App\Models\Annexures_Master;

class PruneDeletedRecords extends Command
{
    protected $signature = 'prune:deleted:records';

    protected $description = 'Prune deleted records and their associated files that are older than the configured prune days.';

    public function handle()
    {
        $pruneDays = config('app.prune_days');
        $threshold = Carbon::now()->subDays($pruneDays);

        $this->info("Pruning records deleted before: {$threshold->toDateTimeString()}");

        $this->pruneLQMs($threshold);
        $this->pruneAnnexures($threshold);

        $this->info("Pruning completed.");
    }

    protected function pruneLQMs($threshold)
    {
        $lqms = LQMs_Master::onlyTrashed()
            ->with(['revisions' => function ($query)
            {
                $query->withTrashed();
            }])
            ->where('deleted_at', '<', $threshold)
            ->get();

        $count = 0;
        foreach ($lqms as $lqm)
        {
            foreach ($lqm->revisions as $revision)
            {
                if ($revision->file_path && Storage::disk('public')->exists($revision->file_path))
                {
                    Storage::disk('public')->delete($revision->file_path);
                }
                $revision->forceDelete();
            }
            $lqm->forceDelete();
            $count++;
        }

        $this->info("Pruned {$count} LQM records.");
    }

    protected function pruneAnnexures($threshold)
    {
        $annexures = Annexures_Master::onlyTrashed()
            ->with(['revisions' => function ($query)
            {
                $query->withTrashed();
            }])
            ->where('deleted_at', '<', $threshold)
            ->get();

        $count = 0;
        foreach ($annexures as $annexure)
        {
            foreach ($annexure->revisions as $revision)
            {
                if ($revision->file_path && Storage::disk('public')->exists($revision->file_path))
                {
                    Storage::disk('public')->delete($revision->file_path);
                }
                $revision->forceDelete();
            }
            $annexure->forceDelete();
            $count++;
        }

        $this->info("Pruned {$count} Annexure records.");
    }
}
