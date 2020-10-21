<?php

namespace App\Admin\Controllers\Fictions\Payments;

use App\Model\Orders\Amount;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AmountController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '支付::金額管理';

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
        $grid = new Grid(new Amount());

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
            $selector->select('vip', __('fictions.amount.vip'), ['0' => '停用', '1' => '啟用']);
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('name', __('fictions.amount.name'));
        $grid->column('description', __('fictions.amount.description'));
        $grid->column('price', __('fictions.amount.price'));
        $grid->column('point_base', __('fictions.amount.point_base'));
        $grid->column('point_give', __('fictions.amount.point_give'));
        $grid->column('points', __('fictions.amount.points'));
        $grid->column('point_cash', __('fictions.amount.point_cash'));
        $grid->column('vip', __('fictions.amount.vip'));
        $grid->column('vip_name', __('fictions.amount.vip_name'));
        $grid->column('vip_day', __('fictions.amount.vip_day'));
        $grid->column('sort', __('fictions.amount.sort'));
        $grid->column('is_default', __('fictions.amount.is_default'))->bool(['0' => false, '1' => true]);
        $grid->column('status', __('fictions.status'))->switch($this->status_arr);
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
        $show = new Show(Amount::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('name', __('fictions.amount.name'));
        $show->field('description', __('fictions.amount.description'));
        $show->field('price', __('fictions.amount.price'));
        $show->field('point_base', __('fictions.amount.point_base'));
        $show->field('point_give', __('fictions.amount.point_give'));
        $show->field('points', __('fictions.amount.points'));
        $show->field('point_cash', __('fictions.amount.point_cash'));
        $show->field('vip', __('fictions.amount.vip'));
        $show->field('vip_name', __('fictions.amount.vip_name'));
        $show->field('vip_day', __('fictions.amount.vip_day'));
        $show->field('sort', __('fictions.amount.sort'));
        $show->field('is_default', __('fictions.amount.is_default'));
        $show->field('status', __('fictions.status'));
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
        $form = new Form(new Amount());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->text('name', __('fictions.amount.name'))->required();
        $form->text('description', __('fictions.amount.description'));
        $form->number('price', __('fictions.amount.price'))->required();
        $form->number('point_base', __('fictions.amount.point_base'));
        $form->number('point_give', __('fictions.amount.point_give'));
        $form->number('points', __('fictions.amount.points'));
        $form->number('point_cash', __('fictions.amount.point_cash'));
        $form->radio('vip', __('fictions.amount.vip'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->text('vip_name', __('fictions.amount.vip_name'));
        $form->number('vip_day', __('fictions.amount.vip_day'));
        $form->number('sort', __('fictions.amount.sort'));
        $form->radio('is_default', __('fictions.amount.is_default'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->switch('status', __('Status'))->states($this->status_arr);

        $form->footer(function ($footer) {
            //$footer->disableReset();// 去掉`重置`按钮
            //$footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        return $form;
    }
}
