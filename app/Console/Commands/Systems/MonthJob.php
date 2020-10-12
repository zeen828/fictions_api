<?php

namespace App\Console\Commands\Systems;

use Illuminate\Console\Command;

class MonthJob extends Command
{
    /**
     * 每月例行公事
     * 
     * 建立: php artisan make:command Systems/MonthJob
     * 執行: php artisan systems:month
     * whereis php
     * vim /etc/crontab
     * systemctl restart crond
     * 0 0 1 * * root /usr/bin/php /usr/share/nginx/website/wt_api/artisan systems:month
     * 
     * @var string
     */
    protected $signature = 'systems:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('[' . date('Y-m-d H:i:s') . '] START');
        $this->info('[' . date('Y-m-d H:i:s') . '] END');
    }
}
