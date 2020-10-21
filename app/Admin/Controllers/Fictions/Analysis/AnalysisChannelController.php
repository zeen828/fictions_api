<?php

namespace App\Admin\Controllers\Fictions\Analysis;

use App\Model\Analysis\AnalysisChannel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Widgets\Box;

class AnalysisChannelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AnalysisChannel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AnalysisChannel());

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
            $bar = view('admin.chartjs.bar');
            return new Box('Bar chart', $bar);
        });

        $grid->column('id', __('Id'));
        $grid->column('y', __('Y'));
        $grid->column('m', __('M'));
        $grid->column('d', __('D'));
        $grid->column('h', __('H'));
        $grid->column('channel_id', __('Channel id'));
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
        $show = new Show(AnalysisChannel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('y', __('Y'));
        $show->field('m', __('M'));
        $show->field('d', __('D'));
        $show->field('h', __('H'));
        $show->field('channel_id', __('Channel id'));
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
        $form = new Form(new AnalysisChannel());

        $form->number('y', __('Y'));
        $form->switch('m', __('M'));
        $form->switch('d', __('D'));
        $form->switch('h', __('H'));
        $form->number('channel_id', __('Channel id'));
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
