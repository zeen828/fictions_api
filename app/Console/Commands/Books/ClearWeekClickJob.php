<?php

namespace App\Console\Commands\Books;

use Illuminate\Console\Command;
use App\Model\Books\Bookinfo;

class ClearWeekClickJob extends Command
{
    /**
     * 每周清除書籍周點籍排名
     * 
     * 建立: php artisan make:command Books/ClearWeekClickJob
     * 執行: php artisan books:clearweekclick
     * whereis php
     * vim /etc/crontab
     * systemctl restart crond
     * 0 0 * * 1 root /usr/bin/php /usr/share/nginx/website/wt_api/artisan books:clearweekclick
     * 
     * @var string
     */
    protected $signature = 'books:clearweekclick';

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
        $abc = Bookinfo::select('id', 'name', 'click_w')->where('click_w', '!=', '0')->where('status', '1')->orderBy('click_w', 'desc')->skip(0)->take(50)->get();
        print_r($abc);
        $this->info('[' . date('Y-m-d H:i:s') . '] END');
    }
}
