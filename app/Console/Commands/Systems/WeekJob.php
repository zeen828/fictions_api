<?php

namespace App\Console\Commands\Systems;

use Illuminate\Console\Command;

class WeekJob extends Command
{
    /**
     * 每周例行公事
     * 
     * 建立: php artisan make:command Systems/WeekJob
     * 執行: php artisan systems:week
     * whereis php
     * vim /etc/crontab
     * systemctl restart crond
     * 0 0 * * 1 root /usr/bin/php /usr/share/nginx/website/wt_api/artisan systems:week
     * 
     * @var string
     */
    protected $signature = 'systems:week';

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
