<?php

namespace App\Admin\Controllers;

use App\Model\Analysis\AnalysisUser;
use DB;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Widgets\Box;

class AnalysisUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AnalysisUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AnalysisUser());

        //條件
        //$grid->model()->active();
        $grid->model()->orderBy('id', 'desc');

        //隱藏功能
        $grid->disableCreateButton();//禁用创建按钮
        //$grid->disablePagination();//禁用分页条
        //$grid->disableFilter();//禁用查询过滤器
        $grid->disableExport();//禁用导出数据按钮
        //$grid->disableRowSelector();//禁用行选择checkbox
        $grid->disableActions();//禁用行操作列
        $grid->disableColumnSelector();//禁用行选择器
        $grid->disableBatchActions();//去掉批量操作
        //禁用行操作各別項目

        $grid->header(function ($query) {
            // 資料準備
            $start_datetime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")." -7 day"));
            $date = date("Y-m-d", strtotime($start_datetime));
            $chart_data = array(
                'labels' => array(),
                'datasets' => array(),
            );
            $label_data = array(
                'label' => '線條1',
                'borderColor' => 'rgb(255, 99, 132)',
                'borderWidth' => 1,
                'data' => array(),
            );
            $user_reg = $label_data;
            $user_reg['label'] = '註冊數';
            $user_reg['borderColor'] = 'rgb(255, 99, 132)';
            $user_login = $label_data;
            $user_login['label'] = '登入數';
            $user_login['borderColor'] = 'rgb(255, 159, 64)';
            $order_all = $label_data;
            $order_all['label'] = '訂單數';
            $order_all['borderColor'] = 'rgb(255, 205, 86)';
            $order_success = $label_data;
            $order_success['label'] = '成功訂單數';
            $order_success['borderColor'] = 'rgb(75, 192, 192)';
            $recharge = $label_data;
            $recharge['label'] = '成功訂單金額';
            $recharge['borderColor'] = 'rgb(54, 162, 235)';

            // 查詢
            $analysis = AnalysisUser::select('date', DB::raw('sum(`wap_user_reg`)+sum(`app_user_reg`) as user_reg,
            sum(`wap_user_login`) + sum(`app_user_login`) as user_login,
            sum(`wap_order_all`) + sum(`app_order_all`) as order_all,
            sum(`wap_order_success`) + sum(`app_order_success`) as order_success,
            sum(`wap_recharge`) + sum(`app_recharge`) as recharge'))->where('date', '>=', $date)->groupBy('date')->orderBy('date', 'asc')->get();//->tosql();//->get();
            if (!$analysis->isEmpty()) {
                foreach ($analysis as $val) {
                    // 標題
                    $chart_data['labels'][] = $val->date;
                    // 線圖
                    $user_reg['data'][] = $val->user_reg;
                    $user_login['data'][] = $val->user_login;
                    $order_all['data'][] = $val->order_all;
                    $order_success['data'][] = $val->order_success;
                    $recharge['data'][] = $val->recharge;
                }
            }

            // 組合
            $chart_data['datasets'] = array(
                $user_reg,
                $user_login,
                $order_all,
                $order_success,
                //$recharge,
            );
    
            $bar = view('admin.analysis.bar', ['chart_title' => '名稱', 'chart_data' => $chart_data]);
            return new Box('Bar chart', $bar);
        });

        $grid->column('id', __('Id'));
        $grid->column('y', __('Y'));
        $grid->column('m', __('M'));
        $grid->column('d', __('D'));
        $grid->column('h', __('H'));
        $grid->column('wap_user_reg', __('Wap user reg'));
        $grid->column('app_user_reg', __('App user reg'));
        $grid->column('wap_user_login', __('Wap user login'));
        $grid->column('app_user_login', __('App user login'));
        $grid->column('wap_order_all', __('Wap order all'));
        $grid->column('app_order_all', __('App order all'));
        $grid->column('wap_order_success', __('Wap order success'));
        $grid->column('app_order_success', __('App order success'));
        $grid->column('wap_recharge', __('Wap recharge'));
        $grid->column('app_recharge', __('App recharge'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AnalysisUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('y', __('Y'));
        $show->field('m', __('M'));
        $show->field('d', __('D'));
        $show->field('h', __('H'));
        $show->field('wap_user_reg', __('Wap user reg'));
        $show->field('app_user_reg', __('App user reg'));
        $show->field('wap_user_login', __('Wap user login'));
        $show->field('app_user_login', __('App user login'));
        $show->field('wap_order_all', __('Wap order all'));
        $show->field('app_order_all', __('App order all'));
        $show->field('wap_order_success', __('Wap order success'));
        $show->field('app_order_success', __('App order success'));
        $show->field('wap_recharge', __('Wap recharge'));
        $show->field('app_recharge', __('App recharge'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AnalysisUser());

        $form->number('y', __('Y'));
        $form->switch('m', __('M'));
        $form->switch('d', __('D'));
        $form->switch('h', __('H'));
        $form->number('wap_user_reg', __('Wap user reg'));
        $form->number('app_user_reg', __('App user reg'));
        $form->number('wap_user_login', __('Wap user login'));
        $form->number('app_user_login', __('App user login'));
        $form->number('wap_order_all', __('Wap order all'));
        $form->number('app_order_all', __('App order all'));
        $form->number('wap_order_success', __('Wap order success'));
        $form->number('app_order_success', __('App order success'));
        $form->decimal('wap_recharge', __('Wap recharge'))->default(0.00);
        $form->decimal('app_recharge', __('App recharge'))->default(0.00);
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
