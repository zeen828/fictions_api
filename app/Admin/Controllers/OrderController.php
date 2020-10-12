<?php

namespace App\Admin\Controllers;

use App\Model\Orders\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '訂單';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

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
        //$grid->actions(function ($actions) {
            //$actions->disableView();// 去掉查看
            //$actions->disableEdit();// 去掉编辑
            //$actions->disableDelete();// 去掉删除
        //});

        //篩選
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('order_sn', __('fictions.order.order_sn'));
            // 设置created_at字段的范围查询
            $filter->between('created_at', __('fictions.created_at'))->datetime();
        });

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', __('fictions.status'), [0 => '未支付', 1 => '支付成功', 2 => '支付失敗']);
        });

        $grid->column('id', __('fictions.id'));
        //$grid->column('user_id', __('fictions.order.user_id'));
        $grid->column('user.account', __('fictions.user.account'));
        //$grid->column('payment_id', __('fictions.order.payment_id'));
        $grid->column('payment.name', __('fictions.payment.name'));
        $grid->column('order_sn', __('fictions.order.order_sn'));
        $grid->column('price', __('fictions.order.price'));
        //$grid->column('point_old', __('fictions.order.point_old'));
        $grid->column('points', __('fictions.order.points'))->expand(function ($model) {
            return sprintf('原始點數:%0d儲值點數:%0d儲值後點數:%0d', $model->point_old, $model->points, $model->point_new);
        });
        //$grid->column('point_new', __('fictions.order.point_new'));
        $grid->column('vip', __('fictions.order.vip'))->expand(function ($model) {
            return sprintf('原始日期:%s增加天數:%0d儲值後日期:%s', $model->vip_at_old, $model->vip_day, $model->vip_at_new);
        });
        //$grid->column('vip_at_old', __('fictions.order.vip_at_old'));
        //$grid->column('vip_day', __('fictions.order.vip_day'));
        //$grid->column('vip_at_new', __('fictions.order.vip_at_new'));
        $grid->column('transaction_sn', __('fictions.order.transaction_sn'))->expand(function ($model) {
            return sprintf('交易訂單:%s交易完成時間:%s', $model->transaction_sn, $model->transaction_at);
        });
        //$grid->column('transaction_at', __('fictions.order.transaction_at'));
        //$grid->column('app', __('fictions.order.app'));
        //$grid->column('linkid', __('fictions.order.linkid'));
        //$grid->column('callbackUrl', __('fictions.order.callbackUrl'));
        //$grid->column('sdk', __('fictions.order.sdk'));
        //$grid->column('config', __('fictions.order.config'));
        $grid->column('status', __('fictions.status'))->using(['0' => '未支付', '1' => '支付成功', '2' => '支付失敗'])->label(['0' => 'warning', '1' => 'success', '2' => 'danger']);
        $grid->column('created_at', __('fictions.created_at'));
        $grid->column('updated_at', __('fictions.updated_at'));

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
        $show = new Show(Order::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('payment_id', __('Payment id'));
        $show->field('order_sn', __('Order sn'));
        $show->field('price', __('Price'));
        $show->field('point_old', __('Point old'));
        $show->field('points', __('Points'));
        $show->field('point_new', __('Point new'));
        $show->field('vip', __('Vip'));
        $show->field('vip_at_old', __('Vip at old'));
        $show->field('vip_day', __('Vip day'));
        $show->field('vip_at_new', __('Vip at new'));
        $show->field('transaction_sn', __('Transaction sn'));
        $show->field('transaction_at', __('Transaction at'));
        $show->field('app', __('App'));
        $show->field('linkid', __('Linkid'));
        $show->field('callbackUrl', __('CallbackUrl'));
        $show->field('sdk', __('Sdk'));
        $show->field('config', __('Config'));
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
        $form = new Form(new Order());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            $tools->disableView();// 去掉`查看`按钮
        });

        $form->number('user_id', __('User id'));
        $form->number('payment_id', __('Payment id'));
        $form->text('order_sn', __('Order sn'));
        $form->number('price', __('Price'));
        $form->number('point_old', __('Point old'));
        $form->number('points', __('Points'));
        $form->number('point_new', __('Point new'));
        $form->switch('vip', __('Vip'));
        $form->datetime('vip_at_old', __('Vip at old'))->default(date('Y-m-d H:i:s'));
        $form->number('vip_day', __('Vip day'));
        $form->datetime('vip_at_new', __('Vip at new'))->default(date('Y-m-d H:i:s'));
        $form->text('transaction_sn', __('Transaction sn'));
        $form->datetime('transaction_at', __('Transaction at'))->default(date('Y-m-d H:i:s'));
        $form->number('app', __('App'));
        $form->number('linkid', __('Linkid'));
        $form->text('callbackUrl', __('CallbackUrl'));
        $form->text('sdk', __('Sdk'));
        $form->textarea('config', __('Config'));
        $form->switch('status', __('Status'));

        $form->footer(function ($footer) {
            $footer->disableReset();// 去掉`重置`按钮
            $footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        return $form;
    }
}
