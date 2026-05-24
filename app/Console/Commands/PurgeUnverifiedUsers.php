<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PurgeUnverifiedUsers extends Command
{
    protected $signature = 'app:purge-unverified-users';

    protected $description = 'Delete unverified users older than 10 days';

    public function handle()
    {
        $count = User::whereNull('email_verified_at')->where('created_at', '<=', Carbon::now()->subDays(10))->delete();

        $this->info("Deleted {$count} unverified user(s).");

        return Command::SUCCESS;
    }
}