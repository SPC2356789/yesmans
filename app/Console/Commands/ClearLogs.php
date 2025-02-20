<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除 Laravel 舊的日誌';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $logPath = storage_path('logs');

        // 刪除所有 .log 檔案
        foreach (glob($logPath . '/*.log') as $logFile) {
            File::delete($logFile);
        }

        $this->info('舊日誌已清除！');
    }
}
