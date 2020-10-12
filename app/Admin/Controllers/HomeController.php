<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Callout;

use Encore\Admin\Widgets\InfoBox;// 訊息套件

use App\Model\Analysis\AnalysisUser;
use DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }

    public function home(Content $content)
    {
        return $content
            ->title('Index')
            ->description('')
            ->row('產品概述')
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    //bg-red    .bg-yellow   .bg-aqua   .bg-blue   .bg-light-blue   .bg-green,
                    //.bg-navy   .bg-teal   .bg-olive   .bg-lime    .bg-orange   .bg-fuchsia   .bg-purple
                    //.bg-maroon    .bg-black
                    $query = AnalysisUser::select(DB::raw('sum(wap_user_acc) as sum_wua, sum(app_user_acc) as sum_aua, sum(wap_user_acc_hour) as sum_wuah, sum(app_user_acc_hour) as sum_auah, sum(wap_user_reg) as sum_wur, sum(app_user_reg) as sum_aur, sum(wap_order_all) as sum_woa, sum(app_order_all) as sum_aoa, sum(wap_order_success) as sum_wos, sum(app_order_success) as sum_aos, sum(wap_recharge) as sum_wr, sum(app_recharge) as sum_ar'))->where('y', date('Y'))->where('m', date('m'))->where('d', date('d'))->first();

                    $infoBox = new InfoBox('日累計活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wua + $query->sum_aua);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('日累計每小時活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wuah + $query->sum_auah);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('日累計會員', 'user-plus', 'aqua', '/admin/fictions/chart/user', $query->sum_wur + $query->sum_aur);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('日累計訂單', 'btc', 'yellow', '/admin/fictions/chart/user', $query->sum_woa + $query->sum_aoa);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('日累計儲值', 'cc-mastercard', 'green', '/admin/fictions/chart/user', $query->sum_wr + $query->sum_ar);
                    $column->append($infoBox->render());
                });

                $row->column(4, function (Column $column) {
                    $query = AnalysisUser::select(DB::raw('sum(wap_user_acc) as sum_wua, sum(app_user_acc) as sum_aua, sum(wap_user_acc_hour) as sum_wuah, sum(app_user_acc_hour) as sum_auah, sum(wap_user_reg) as sum_wur, sum(app_user_reg) as sum_aur, sum(wap_order_all) as sum_woa, sum(app_order_all) as sum_aoa, sum(wap_order_success) as sum_wos, sum(app_order_success) as sum_aos, sum(wap_recharge) as sum_wr, sum(app_recharge) as sum_ar'))->where('y', date('Y'))->where('m', date('m'))->first();

                    $infoBox = new InfoBox('月累計活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wua + $query->sum_aua);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('月累計每小時活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wuah + $query->sum_auah);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('月累計會員', 'user-plus', 'aqua', '/admin/fictions/chart/user', $query->sum_wur + $query->sum_aur);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('月累計訂單', 'btc', 'yellow', '/admin/fictions/chart/user', $query->sum_woa + $query->sum_aoa);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('月累計儲值', 'cc-mastercard', 'green', '/admin/fictions/chart/user', $query->sum_wr + $query->sum_ar);
                    $column->append($infoBox->render());
                });

                $row->column(4, function (Column $column) {
                    $query = AnalysisUser::select(DB::raw('sum(wap_user_acc) as sum_wua, sum(app_user_acc) as sum_aua, sum(wap_user_acc_hour) as sum_wuah, sum(app_user_acc_hour) as sum_auah, sum(wap_user_reg) as sum_wur, sum(app_user_reg) as sum_aur, sum(wap_order_all) as sum_woa, sum(app_order_all) as sum_aoa, sum(wap_order_success) as sum_wos, sum(app_order_success) as sum_aos, sum(wap_recharge) as sum_wr, sum(app_recharge) as sum_ar'))->first();

                    $infoBox = new InfoBox('總累計活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wua + $query->sum_aua);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('總累計每小時活耀', 'bolt', 'orange', '/admin/fictions/chart/user', $query->sum_wuah + $query->sum_auah);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('總累計會員', 'user-plus', 'aqua', '/admin/fictions/chart/user', $query->sum_wur + $query->sum_aur);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('總累計訂單', 'btc', 'yellow', '/admin/fictions/chart/user', $query->sum_woa + $query->sum_aoa);
                    $column->append($infoBox->render());

                    $infoBox = new InfoBox('總累計儲值', 'cc-mastercard', 'green', '/admin/fictions/chart/user', $query->sum_wr + $query->sum_ar);
                    $column->append($infoBox->render());
                });
            });
    }

    public function chart(Content $content)
    {
        return $content
            ->title($title = 'Chartjs')
            //->row($this->info('https://github.com/laravel-admin-extensions/chartjs', $title))
            ->row(function (Row $row) {

                $bar = view('admin.chartjs.bar');
                $row->column(1/3, new Box('Bar chart', $bar));

                $scatter = view('admin.chartjs.scatter');
                $row->column(1/3, new Box('Scatter chart', $scatter));

                $bar = view('admin.chartjs.line');
                $row->column(1/3, new Box('Line chart', $bar));

            })->row(function (Row $row) {

                $bar = view('admin.chartjs.doughnut');
                $row->column(1/3, new Box('Doughnut chart', $bar));

                $scatter = view('admin.chartjs.combo-bar-line');
                $row->column(1/3, new Box('Chart.js Combo Bar Line Chart', $scatter));

                $bar = view('admin.chartjs.line-stacked');
                $row->column(1/3, new Box('Chart.js Line Chart - Stacked Area', $bar));

            });
    }

    protected function info($url, $title)
    {
        $content = "<a href=\"{$url}\" target='_blank'>{$url}</a>";

        return new Callout($content, $title, 'info');
    }
}
