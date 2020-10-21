<?php

namespace App\Admin\Controllers\Fictions\Chart;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

use App\Model\Analysis\AnalysisUser;
use App\Model\Books\Bookinfo;
use DB;
use Redis;

class ChartController extends Controller
{
    private $colors = array(
        'red' => 'rgb(255, 99, 132)',
        'orange' => 'rgb(255, 159, 64)',
        'yellow' => 'rgb(255, 205, 86)',
        'green' => 'rgb(75, 192, 192)',
        'blue' => 'rgb(54, 162, 235)',
        'purple' => 'rgb(153, 102, 255)',
        'grey' => 'rgb(201, 203, 207)',
        'demo1' => 'rgb(255, 100, 100)',
        'demo2' => 'rgb(100, 255, 100)',
        'demo3' => 'rgb(100, 100, 255)',
    );

    public function index(Content $content)
    {
        return App::abort(404);
    }

    private function getFormatUserData()
    {
        $formatData = array(
            'chart_data' => array(
                'labels' => array(),
                'datasets' => array(),
            ),
            'user_acc' => array(
                'label' => '每日活耀',
                'borderColor' => $this->colors['red'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'user_acc_hour' => array(
                'label' => '每小時活耀',
                'borderColor' => $this->colors['orange'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'user_reg' => array(
                'label' => '註冊數',
                'borderColor' => $this->colors['yellow'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'user_login' => array(
                'label' => '登入數',
                'borderColor' => $this->colors['green'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'order_all' => array(
                'label' => '訂單數',
                'borderColor' => $this->colors['blue'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'order_success' => array(
                'label' => '成功訂單數',
                'borderColor' => $this->colors['purple'],
                'borderWidth' => 1,
                'data' => array(),
            ),
            'recharge' => array(
                'label' => '成功訂單金額',
                'borderColor' => $this->colors['grey'],
                'borderWidth' => 1,
                'data' => array(),
            ),
        );
        return $formatData;
    }

    public function user(Content $content)
    {

        return $content
            ->title('統計圖表')
            ->description('會員統計')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    // 資料準備
                    $start_datetime = date("Y-m-d H:i:s");
                    $date = date("Y-m-d", strtotime($start_datetime));
                    $formatData = $this->getFormatUserData();
                    $chart_data = $formatData['chart_data'];

                    // 查詢
                    $analysis = AnalysisUser::select('h', DB::raw('sum(`wap_user_acc`)+sum(`app_user_acc`) as user_acc, sum(`wap_user_acc_hour`)+sum(`app_user_acc_hour`) as user_acc_hour, sum(`wap_user_reg`)+sum(`app_user_reg`) as user_reg, sum(`wap_user_login`) + sum(`app_user_login`) as user_login, sum(`wap_order_all`) + sum(`app_order_all`) as order_all, sum(`wap_order_success`) + sum(`app_order_success`) as order_success, sum(`wap_recharge`) + sum(`app_recharge`) as recharge'))
                    ->where('date', $date)->groupBy('h')->orderBy('h', 'asc')->get();//->tosql();//->get();

                    // 整理資料
                    if (!$analysis->isEmpty()) {
                        foreach ($analysis as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->h;
                            // 線圖
                            $formatData['user_acc']['data'][] = $val->user_acc;
                            $formatData['user_acc_hour']['data'][] = $val->user_acc_hour;
                            $formatData['user_reg']['data'][] = $val->user_reg;
                            $formatData['user_login']['data'][] = $val->user_login;
                            $formatData['order_all']['data'][] = $val->order_all;
                            $formatData['order_success']['data'][] = $val->order_success;
                            $formatData['recharge']['data'][] = $val->recharge;
                        }
                    }

                    // 組合資料
                    $chart_data['datasets'] = array(
                        $formatData['user_acc'],
                        $formatData['user_acc_hour'],
                        $formatData['user_reg'],
                        $formatData['user_login'],
                        $formatData['order_all'],
                        $formatData['order_success'],
                        //$formatData['recharge'],
                    );
            
                    $bar = view('admin.analysis.bar', ['chart_title' => '會員動向', 'chart_id' => 'chart_01', 'chart_data' => $chart_data]);
                    $column->append(new Box('今日報表', $bar));
                });

                $row->column(6, function (Column $column) {
                    // 資料準備
                    $start_datetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")." -6 day"));
                    $date = date("Y-m-d", strtotime($start_datetime));
                    $formatData = $this->getFormatUserData();
                    $chart_data = $formatData['chart_data'];

                    // 查詢
                    $analysis = AnalysisUser::select('date', DB::raw('sum(`wap_user_acc`)+sum(`app_user_acc`) as user_acc, sum(`wap_user_acc_hour`)+sum(`app_user_acc_hour`) as user_acc_hour, sum(`wap_user_reg`)+sum(`app_user_reg`) as user_reg, sum(`wap_user_login`) + sum(`app_user_login`) as user_login, sum(`wap_order_all`) + sum(`app_order_all`) as order_all, sum(`wap_order_success`) + sum(`app_order_success`) as order_success, sum(`wap_recharge`) + sum(`app_recharge`) as recharge'))
                    ->where('date', '>=', $date)->groupBy('date')->orderBy('date', 'asc')->get();//->tosql();//->get();

                    // 整理資料
                    if (!$analysis->isEmpty()) {
                        foreach ($analysis as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->date;
                            // 線圖
                            $formatData['user_acc']['data'][] = $val->user_acc;
                            $formatData['user_acc_hour']['data'][] = $val->user_acc_hour;
                            $formatData['user_reg']['data'][] = $val->user_reg;
                            $formatData['user_login']['data'][] = $val->user_login;
                            $formatData['order_all']['data'][] = $val->order_all;
                            $formatData['order_success']['data'][] = $val->order_success;
                            $formatData['recharge']['data'][] = $val->recharge;
                        }
                    }

                    // 組合資料
                    $chart_data['datasets'] = array(
                        $formatData['user_acc'],
                        $formatData['user_acc_hour'],
                        $formatData['user_reg'],
                        $formatData['user_login'],
                        $formatData['order_all'],
                        $formatData['order_success'],
                        //$formatData['recharge'],
                    );
            
                    $bar = view('admin.analysis.bar', ['chart_title' => '會員動向', 'chart_id' => 'chart_02', 'chart_data' => $chart_data]);
                    $column->append(new Box('近七天報表', $bar));
                });
            })
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    // 資料準備
                    $start_datetime = date("Y-m-d H:i:s");
                    $date = date("Y-m-01", strtotime($start_datetime));
                    $formatData = $this->getFormatUserData();
                    $chart_data = $formatData['chart_data'];

                    // 查詢
                    $analysis = AnalysisUser::select('date', DB::raw('sum(`wap_user_acc`)+sum(`app_user_acc`) as user_acc, sum(`wap_user_acc_hour`)+sum(`app_user_acc_hour`) as user_acc_hour, sum(`wap_user_reg`)+sum(`app_user_reg`) as user_reg, sum(`wap_user_login`) + sum(`app_user_login`) as user_login, sum(`wap_order_all`) + sum(`app_order_all`) as order_all, sum(`wap_order_success`) + sum(`app_order_success`) as order_success, sum(`wap_recharge`) + sum(`app_recharge`) as recharge'))
                    ->where('date', '>=', $date)->groupBy('date')->orderBy('date', 'asc')->get();//->tosql();//->get();

                    // 整理資料
                    if (!$analysis->isEmpty()) {
                        foreach ($analysis as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->date;
                            // 線圖
                            $formatData['user_acc']['data'][] = $val->user_acc;
                            $formatData['user_acc_hour']['data'][] = $val->user_acc_hour;
                            $formatData['user_reg']['data'][] = $val->user_reg;
                            $formatData['user_login']['data'][] = $val->user_login;
                            $formatData['order_all']['data'][] = $val->order_all;
                            $formatData['order_success']['data'][] = $val->order_success;
                            $formatData['recharge']['data'][] = $val->recharge;
                        }
                    }

                    // 組合資料
                    $chart_data['datasets'] = array(
                        $formatData['user_acc'],
                        $formatData['user_acc_hour'],
                        $formatData['user_reg'],
                        $formatData['user_login'],
                        $formatData['order_all'],
                        $formatData['order_success'],
                        //$formatData['recharge'],
                    );
            
                    $bar = view('admin.analysis.bar', ['chart_title' => '會員動向', 'chart_id' => 'chart_03', 'chart_data' => $chart_data]);
                    $column->append(new Box('當月報表', $bar));
                });

                $row->column(6, function (Column $column) {
                    //$bar = view('admin.analysis.line', ['chart_title' => '名稱', 'chart_id' => 'chart_04', 'chart_data' => null]);
                    //$column->append(new Box('Bar chart', $bar));
                });
            });
    }

    public function book(Content $content)
    {

        return $content
            ->title('統計圖表')
            ->description('書籍統計')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    // 資料準備
                    $chart_data = array(
                        'datasets' => array(
                            array(
                                'data' => array(),
                                'backgroundColor' => array(
                                    $this->colors['red'],
                                    $this->colors['orange'],
                                    $this->colors['yellow'],
                                    $this->colors['green'],
                                    $this->colors['blue'],
                                    $this->colors['purple'],
                                    $this->colors['grey'],
                                    $this->colors['demo1'],
                                    $this->colors['demo2'],
                                    $this->colors['demo3'],
                                ),
                                'label' => 'Dataset 1',
                            )
                        ),
                        'labels' => array(),
                    );
                    $books = Bookinfo::where('click_w', '!=', '0')->where('status', '1')->orderBy('click_w', 'desc')->skip(0)->take(20)->get();
                    if (!$books->isEmpty()) {
                        foreach ($books as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->name;
                            // 觀看率
                            $chart_data['datasets']['0']['data'][] = $val->click_w;
                        }
                    }
                    $bar = view('admin.analysis.doughnut', ['chart_title' => '周排行', 'chart_id' => 'chart_01', 'chart_data' => $chart_data]);
                    $column->append(new Box('周排行', $bar));
                });

                $row->column(6, function (Column $column) {
                    // 資料準備
                    $chart_data = array(
                        'datasets' => array(
                            array(
                                'data' => array(),
                                'backgroundColor' => array(
                                    $this->colors['red'],
                                    $this->colors['orange'],
                                    $this->colors['yellow'],
                                    $this->colors['green'],
                                    $this->colors['blue'],
                                    $this->colors['purple'],
                                    $this->colors['grey'],
                                    $this->colors['demo1'],
                                    $this->colors['demo2'],
                                    $this->colors['demo3'],
                                ),
                                'label' => 'Dataset 1',
                            )
                        ),
                        'labels' => array(),
                    );
                    $books = Bookinfo::where('click_m', '!=', '0')->where('status', '1')->orderBy('click_m', 'desc')->skip(0)->take(20)->get();
                    if (!$books->isEmpty()) {
                        foreach ($books as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->name;
                            // 觀看率
                            $chart_data['datasets']['0']['data'][] = $val->click_m;
                        }
                    }
                    $bar = view('admin.analysis.doughnut', ['chart_title' => '月排行', 'chart_id' => 'chart_02', 'chart_data' => $chart_data]);
                    $column->append(new Box('月排行', $bar));
                });
            })
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    // 資料準備
                    $chart_data = array(
                        'datasets' => array(
                            array(
                                'data' => array(),
                                'backgroundColor' => array(
                                    $this->colors['red'],
                                    $this->colors['orange'],
                                    $this->colors['yellow'],
                                    $this->colors['green'],
                                    $this->colors['blue'],
                                    $this->colors['purple'],
                                    $this->colors['grey'],
                                    $this->colors['demo1'],
                                    $this->colors['demo2'],
                                    $this->colors['demo3'],
                                ),
                                'label' => 'Dataset 1',
                            )
                        ),
                        'labels' => array(),
                    );
                    $books = Bookinfo::where('click_s', '!=', '0')->where('status', '1')->orderBy('click_s', 'desc')->skip(0)->take(20)->get();
                    if (!$books->isEmpty()) {
                        foreach ($books as $val) {
                            // 標題
                            $chart_data['labels'][] = $val->name;
                            // 觀看率
                            $chart_data['datasets']['0']['data'][] = $val->click_s;
                        }
                    }
                    $bar = view('admin.analysis.doughnut', ['chart_title' => '總排行', 'chart_id' => 'chart_03', 'chart_data' => $chart_data]);
                    $column->append(new Box('總排行', $bar));
                });

                $row->column(6, function (Column $column) {
                    // Redis
                    $redisKey = sprintf('admin_chart_book_%s', 'click_o');
                    if ($redisVal = Redis::get($redisKey)) {
                        $chart_data = unserialize($redisVal);
                    } else {
                        // 資料準備
                        $chart_data = array(
                            'datasets' => array(
                                array(
                                    'data' => array(),
                                    'backgroundColor' => array(
                                        $this->colors['red'],
                                        $this->colors['orange'],
                                        $this->colors['yellow'],
                                        $this->colors['green'],
                                        $this->colors['blue'],
                                        $this->colors['purple'],
                                        $this->colors['grey'],
                                        $this->colors['demo1'],
                                        $this->colors['demo2'],
                                        $this->colors['demo3'],
                                    ),
                                    'label' => 'Dataset 1',
                                )
                            ),
                            'labels' => array(),
                        );
                        // 更新資料
                        $books = Bookinfo::where('click_o', '!=', '0')->where('status', '1')->orderBy('click_o', 'desc')->skip(0)->take(20)->get();
                        if (!$books->isEmpty()) {
                            foreach ($books as $val) {
                                // 標題
                                $chart_data['labels'][] = $val->name;
                                // 觀看率
                                $chart_data['datasets']['0']['data'][] = $val->click_o;
                            }
                        }
                        // 寫
                        Redis::set($redisKey, serialize($chart_data), 'EX', 31536000);// 60 * 60 * 24 * 365 一年
                    }

                    $bar = view('admin.analysis.doughnut', ['chart_title' => '外頭舊排行', 'chart_id' => 'chart_04', 'chart_data' => $chart_data]);
                    $column->append(new Box('外頭舊排行', $bar));
                });
            });
    }

    public function channel(Content $content)
    {

        return $content
            ->title('統計圖表')
            ->description('渠道統計')
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    //$bar = view('admin.analysis.line', ['chart_title' => '名稱', 'chart_id' => 'chart_02', 'chart_data' => null]);
                    //$column->append(new Box('Bar chart', $bar));
                });

                $row->column(6, function (Column $column) {
                    //$bar = view('admin.analysis.line', ['chart_title' => '名稱', 'chart_id' => 'chart_02', 'chart_data' => null]);
                    //$column->append(new Box('Bar chart', $bar));
                });
            })
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    //$bar = view('admin.analysis.line', ['chart_title' => '名稱', 'chart_id' => 'chart_03', 'chart_data' => null]);
                    //$column->append(new Box('Bar chart', $bar));
                });

                $row->column(6, function (Column $column) {
                    //$bar = view('admin.analysis.line', ['chart_title' => '名稱', 'chart_id' => 'chart_04', 'chart_data' => null]);
                    //$column->append(new Box('Bar chart', $bar));
                });
            });
    }
}
