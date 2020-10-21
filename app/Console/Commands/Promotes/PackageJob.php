<?php

namespace App\Console\Commands\Promotes;

use Illuminate\Console\Command;

use App\Model\Promotes\ChannelApk;
use Redis;

class PackageJob extends Command
{
    /**
     * 渠道包打包排程-透過supervisord
     * 
     * 建立: php artisan make:command Promotes/PackageJob
     * 執行: php artisan promotes:package
     * systemctl restart supervisord
     * systemctl status supervisord
     * 
     * @var string
     */
    protected $signature = 'promotes:package';

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
        while (true) {
            // 工作區
            $crons = ChannelApk::where('status', 0)->get();
            foreach ($crons as $cron) {
                $this->info('[' . date('Y-m-d H:i:s') . '] START');
                // Redis 清除
                $redisKey = sprintf('channelapk_join_apk_bychannelid%d', $cron->channel_id);
                Redis::expire($redisKey, 0);
                //print_r($cron->channel);
                //print_r($cron->apk);
                $url = sprintf('http://127.0.0.1:8081/channel/assemble?channelId=%d&version=%s&outUrl=/&apkBaseName=%s&apkOutName=%s', $cron->channel->id, $cron->apk->version, $cron->apk->apk, $cron->id);
                $this->info($url);
                //curl "127.0.0.1:8081/channel/assemble?channelId=2&version=1.1.0&apkBaseName=admin/apk/1/q_V1.1.apk"
                $ch = curl_init();//初始化curl
                curl_setopt($ch, CURLOPT_URL, $url);//設定url屬性
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $output = curl_exec($ch);//獲取資料
                curl_close($ch);//關閉curl
                //$this->info($output);
                $output = json_decode($output);
                //print_r($output);
                //print_r($output->success);
                if ($output->success == true) {
                    $cron->uri = $output->data->path;
                    $cron->status = 1;
                    $cron->save();
                    $this->info(sprintf('[' . date('Y-m-d H:i:s') . '] Channel %d success', $cron->channel->id));
                } else {
                    $this->info(sprintf('[' . date('Y-m-d H:i:s') . '] Channel %d error', $cron->channel->id));
                }
                $this->info('[' . date('Y-m-d H:i:s') . '] END');
            }
            sleep(10);
        }
        // 結束
    }
}
