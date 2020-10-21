<?php

namespace App\Admin\Controllers\Fictions\Payments;

use App\Model\Orders\Payment;
use App\Model\Orders\Amount;
use Redis;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '支付::支付管理';

    public $status_arr = [
        'on' => ['value' => 0, 'text' => '封鎖', 'color' => 'default'],
        'off' => ['value' => 1, 'text' => '啟用', 'color' => 'primary'],
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Payment());

        //條件
        //$grid->model()->active();

        //隱藏功能
        //$grid->disableCreateButton();//禁用创建按钮
        //$grid->disablePagination();//禁用分页条
        //$grid->disableFilter();//禁用查询过滤器
        $grid->disableExport();//禁用导出数据按钮
        //$grid->disableRowSelector();//禁用行选择checkbox
        //$grid->disableActions();//禁用行操作列
        $grid->disableColumnSelector();//禁用行选择器
        $grid->disableBatchActions();//去掉批量操作
        //禁用行操作各別項目
        $grid->actions(function ($actions) {
            $actions->disableView();// 去掉查看
            //$actions->disableEdit();// 去掉编辑
            $actions->disableDelete();// 去掉删除
        });

        //篩選
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', __('fictions.amount.name'));
        });

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('client', __('fictions.payment.client'), ['0' => '全部', '1' => 'Wap', '2' => 'App']);
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用', '2' => '測試']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('name', __('fictions.payment.name'));
        $grid->column('description', __('fictions.payment.description'));
        $grid->column('domain', __('fictions.payment.domain'));
        $grid->column('domain_call', __('fictions.payment.domain_call'));
        $grid->column('sdk', __('fictions.payment.sdk'))->using(['Weixin' => '微信', 'Alipay' => '支付寶', 'Other' => '其他'])->label(['Weixin' => 'success', 'Alipay' => 'info', 'Other' => 'danger']);
        //$grid->column('sdk2', __('fictions.payment.sdk2'));
        $grid->column('limit', __('fictions.payment.limit'));
        $grid->column('ratio', __('fictions.payment.ratio'));
        $grid->column('client', __('fictions.payment.client'))->using(['0' => '全部', '1' => 'Wap', '2' => 'App']);
        $grid->column('float', __('fictions.payment.float'))->bool(['0' => false, '1' => true]);
        //$grid->column('min', __('fictions.payment.min'));
        //$grid->column('max', __('fictions.payment.max'));
        //$grid->column('config', __('fictions.payment.config'));
        $grid->column('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用', '2' => '測試'])->label(['0' => 'danger', '1' => 'success', '2' => 'info']);
        //$grid->column('created_at', __('fictions.created_at'));
        //$grid->column('updated_at', __('fictions.updated_at'));

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
        $show = new Show(Payment::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('name', __('fictions.payment.name'));
        $show->field('description', __('fictions.payment.description'));
        $show->field('domain', __('fictions.payment.domain'));
        $show->field('domain_call', __('fictions.payment.domain_call'));
        $show->field('sdk', __('fictions.payment.sdk'));
        //$show->field('sdk2', __('fictions.payment.sdk2'));
        $show->field('limit', __('fictions.payment.limit'));
        $show->field('ratio', __('fictions.payment.ratio'));
        $show->field('client', __('fictions.payment.client'));
        $show->field('float', __('fictions.payment.float'));
        $show->field('min', __('fictions.payment.min'));
        $show->field('max', __('fictions.payment.max'));
        //$show->field('config', __('fictions.payment.config'));
        $show->field('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用', '2' => '測試']);
        $show->field('created_at', __('fictions.created_at'));
        $show->field('updated_at', __('fictions.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Payment());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->text('name', __('fictions.payment.name'))->required();
        $form->text('description', __('fictions.payment.description'));
        $form->text('domain', __('fictions.payment.domain'));
        $form->text('domain_call', __('fictions.payment.domain_call'));
        $form->radio('sdk', __('fictions.payment.sdk'))->options(['Weixin' => '微信', 'Alipay' => '支付寶', 'Other' => '其他'])->default(0);
        //$form->text('sdk2', __('fictions.payment.sdk2'));
        $form->number('limit', __('fictions.payment.limit'))->default(0);
        $form->decimal('ratio', __('fictions.payment.ratio'))->default(0.00);
        $form->radio('client', __('fictions.payment.client'))->options(['0' => '全部', '1' => 'Wap', '2' => 'App'])->default(0);
        $form->radio('float', __('fictions.payment.float'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->number('min', __('fictions.payment.min'))->default(0)->help('單位分');
        $form->number('max', __('fictions.payment.max'))->default(0)->help('單位分');
        // 金額關係
        $form->checkbox('amount', '支付金額')->options(Amount::all()->pluck('name', 'id'));
        //$form->textarea('config', __('fictions.payment.config'));
        //$form->keyValue('config', __('fictions.payment.config'))->default('{}');
        $form->keyValue('config')->default('{}');
        $form->radio('status', __('Status'))->options(['0' => '停用', '1' => '啟用', '2' => '測試'])->default(2);

        $form->footer(function ($footer) {
            //$footer->disableReset();// 去掉`重置`按钮
            //$footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        //保存后回调
        $form->saved(function (Form $form) {
            // Redis 清除
            $redisKey = sprintf('amount_all');
            Redis::expire($redisKey, 0);
        });

        return $form;
    }
}
