<?php

namespace App\Console\Commands\Analysis;

use Illuminate\Console\Command;

use App\Model\Users\User;
use App\Model\Orders\Order;
use App\Model\Promotes\Channel;
use App\Model\Analysis\LogsUsersAccess;
use App\Model\Analysis\AnalysisChannel;
use DB;

class ChannelHourJob extends Command
{
    /**
     * 每小時渠道統計整理
     * 
     * 建立: php artisan make:command Analysis/ChannelHourJob
     * 執行: php artisan analysis:channelhour
     * 執行: php artisan analysis:channelhour --start_datetime=20200930010000
     * whereis php
     * vim /etc/crontab
     * systemctl restart crond
     * 15 * * * * root /usr/bin/php /usr/share/nginx/website/wt_api/artisan analysis:channelhour
     * 
     * @var string
     */
    protected $signature = 'analysis:channelhour {--start_datetime= : 查詢日EX=20200101010000}';

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
        // 起始日期
        $start_datetime = $this->option('start_datetime');
        if (!empty($start_datetime)) {
            $start_datetime = date("Y-m-d H:i:s", strtotime($start_datetime));
        } else {
            $start_datetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")." -1 hour"));
        }

        $y = date("Y", strtotime($start_datetime));
        $m = date("m", strtotime($start_datetime));
        $d = date("d", strtotime($start_datetime));
        $h = date("H", strtotime($start_datetime));
        $start_hour = sprintf('%s-%s-%s %s', $y, $m, $d, $h);
        $end_hour = $start_hour;
        $start_at = date("Y-m-d H:i:s", strtotime($start_hour.":00:00"));
        $end_at = date("Y-m-d H:i:s", strtotime($end_hour.":59:59"));
        $this->info('統計時段:' . $start_at . '~' . $end_at);

        //APP(1:WAP,2:APP)

        $channels = Channel::where('status', '1')->get();
        if (!$channels->isEmpty()) {
            foreach ($channels as $channel) {

                // 會員 - 活耀
                $wap_user_acc = 0;
                $app_user_acc = 0;
                $user_acc = LogsUsersAccess::select('app', DB::raw('count(id) as coun'))->where('link_id', $channel->id)->whereBetween('created_at', [$start_at, $end_at])->groupBy('app')->get();
                if (!$user_acc->isEmpty()) {
                    foreach ($user_acc as $val) {
                        switch ($val->app) {
                            case 1:
                                $wap_user_acc = $val->coun;
                                break;
                            case 2:
                                $app_user_acc = $val->coun;
                                break;
                            default:
                                break;
                        }
                    }
                }

                // 會員 - 註冊
                $wap_user_reg = 0;
                $app_user_reg = 0;
                $user_reg = User::select('app', DB::raw('count(id) as coun'))->where('link_id', $channel->id)->whereBetween('created_at', [$start_at, $end_at])->groupBy('app')->get();
                if (!$user_reg->isEmpty()) {
                    foreach ($user_reg as $val) {
                        switch ($val->app) {
                            case 1:
                                $wap_user_reg = $val->coun;
                                break;
                            case 2:
                                $app_user_reg = $val->coun;
                                break;
                            default:
                                break;
                        }
                    }
                }

                // 會員 - 登入
                $wap_user_login = 0;
                $app_user_login = 0;
                $user_login = User::select('app', DB::raw('count(id) as coun'))->where('link_id', $channel->id)->whereBetween('updated_at', [$start_at, $end_at])->groupBy('app')->get();
                if (!$user_login->isEmpty()) {
                    foreach ($user_login as $val) {
                        switch ($val->app) {
                            case 1:
                                $wap_user_login = $val->coun;
                                break;
                            case 2:
                                $app_user_login = $val->coun;
                                break;
                            default:
                                break;
                        }
                    }
                }

                // 訂單 - 訂單數
                $wap_order_all = 0;
                $app_order_all = 0;
                $order_all = Order::select('app', DB::raw('count(id) as coun'))->where('link_id', $channel->id)->whereBetween('created_at', [$start_at, $end_at])->groupBy('app')->get();
                if (!$order_all->isEmpty()) {
                    foreach ($order_all as $val) {
                        switch ($val->app) {
                            case 1:
                                $wap_order_all = $val->coun;
                                break;
                            case 2:
                                $app_order_all = $val->coun;
                                break;
                            default:
                                break;
                        }
                    }
                }

                // 訂單 - 成功訂單數
                $wap_order_success = 0;
                $app_order_success = 0;
                $wap_recharge = 0;
                $app_recharge = 0;
                $order_success = Order::select('app', DB::raw('count(id) as coun, sum(price) as sum_price'))->where('status', '1')->where('link_id', $channel->id)->whereBetween('created_at', [$start_at, $end_at])->groupBy('app')->get();
                if (!$order_success->isEmpty()) {
                    foreach ($order_success as $val) {
                        switch ($val->app) {
                            case 1:
                                $wap_order_success = $val->coun;
                                $wap_recharge = $val->sum_price;
                                break;
                            case 2:
                                $app_order_success = $val->coun;
                                $app_recharge = $val->sum_price;
                                break;
                            default:
                                break;
                        }
                    }
                }

                // 存檔資料
                $saveData = array(
                    'y' => $y,
                    'm' => $m,
                    'd' => $d,
                    'h' => $h,
                    'date' => sprintf('%s-%s-%s', $y, $m, $d),
                    'channel_id' => $channel->id,
                    'wap_user_acc' => $wap_user_acc,
                    'app_user_acc' => $app_user_acc,
                    'wap_user_reg' => $wap_user_reg,
                    'app_user_reg' => $app_user_reg,
                    'wap_user_login' => $wap_user_login,
                    'app_user_login' => $app_user_login,
                    'wap_order_all' => $wap_order_all,
                    'app_order_all' => $app_order_all,
                    'wap_order_success' => $wap_order_success,
                    'app_order_success' => $app_order_success,
                    'wap_recharge' => $wap_recharge,
                    'app_recharge' => $app_recharge,
                    'status' => 1,
                );
                //print_r($saveData);
                $analysischannel = AnalysisChannel::where('y', $y)->where('m', $m)->where('d', $d)->where('h', $h)->where('channel_id', $channel->id)->first();
                if (empty($analysischannel)) {
                    // 新增
                    AnalysisChannel::create($saveData);
                } else {
                    // 更新
                    $analysischannel->update($saveData);
                }
            }
        }

        $this->info('[' . date('Y-m-d H:i:s') . '] END');
    }
}
