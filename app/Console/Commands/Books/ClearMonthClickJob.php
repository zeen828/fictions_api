<?php

namespace App\Console\Commands\Books;

use Illuminate\Console\Command;
use App\Model\Books\Bookinfo;

class ClearMonthClickJob extends Command
{
    /**
     * 每月清除書籍月點籍排名
     * 
     * 建立: php artisan make:command Books/ClearMonthClickJob
     * 執行: php artisan books:clearmonthclick
     * whereis php
     * vim /etc/crontab
     * systemctl restart crond
     * 0 0 1 * * root /usr/bin/php /usr/share/nginx/website/wt_api/artisan books:clearmonthclick
     * 
     * @var string
     */
    protected $signature = 'books:clearmonthclick';

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
