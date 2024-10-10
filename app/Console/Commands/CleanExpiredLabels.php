<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Parcel;
use Carbon\Carbon;

class CleanExpiredLabels extends Command
{
    protected $signature = 'labels:clean';
    protected $description = 'Delete parcels with expired labels';

    public function handle()
    {
        $deletedCount = Parcel::where('label_expiry', '<', Carbon::now())->delete();

        $this->info("Deleted {$deletedCount} parcels with expired labels.");
    }
}