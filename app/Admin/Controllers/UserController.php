<?php

namespace App\Admin\Controllers;

use App\Model\Users\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '會員';

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
        $grid = new Grid(new User());

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
            $filter->like('account', __('fictions.user.account'));
            $filter->like('nick_name', __('fictions.user.nick_name'));
            // 设置created_at字段的范围查询
            $filter->between('created_at', __('fictions.created_at'))->datetime();
        });

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('vip', __('fictions.user.vip'), ['0' => '不是', '1' => '是']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('account', __('fictions.user.account'));
        //$grid->column('password', __('fictions.user.password'));
        $grid->column('nick_name', __('fictions.user.nick_name'));
        $grid->column('phone', __('fictions.user.phone'));
        $grid->column('sex', __('fictions.user.sex'))->using(['0' => '未知', '1' => '男', '2' => '女']);
        $grid->column('points', __('fictions.user.points'));//->editable();//清單編輯
        //$grid->column('channel_id', __('fictions.user.channel_id'));
        //$grid->column('link_id', __('fictions.user.link_id'));
        $grid->column('vip', __('fictions.user.vip'))->using(['0' => '不是', '1' => '是']);
        $grid->column('vip_at', __('fictions.user.vip_at'));
        $grid->column('remarks', __('fictions.user.remarks'));
        //$grid->column('token_jwt', __('fictions.user.token_jwt'));
        //$grid->column('remember_token', __('fictions.user.remember_token'));
        $grid->column('status', __('fictions.status'))->switch($this->status_arr);
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
        $show = new Show(User::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('Id'));
        $show->field('account', __('Account'));
        $show->field('password', __('Password'));
        $show->field('nick_name', __('Nick name'));
        $show->field('phone', __('Phone'));
        $show->field('sex', __('Sex'));
        $show->field('points', __('Points'));
        $show->field('channel_id', __('Channel id'));
        $show->field('link_id', __('Link id'));
        $show->field('vip', __('Vip'));
        $show->field('vip_at', __('Vip at'));
        $show->field('status', __('Status'));
        $show->field('remarks', __('Remarks'));
        $show->field('token_jwt', __('Token jwt'));
        $show->field('remember_token', __('Remember token'));
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
        $form = new Form(new User());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            $tools->disableView();// 去掉`查看`按钮
        });

        $form->text('account', __('Account'));
        $form->password('password', __('Password'));
        $form->text('nick_name', __('Nick name'));
        $form->mobile('phone', __('Phone'));
        $form->switch('sex', __('Sex'));
        $form->number('points', __('Points'));
        $form->number('channel_id', __('Channel id'));
        $form->number('link_id', __('Link id'));
        $form->switch('vip', __('Vip'));
        $form->datetime('vip_at', __('Vip at'))->default(date('Y-m-d H:i:s'));
        $form->switch('status', __('Status'))->default(1);
        $form->textarea('remarks', __('Remarks'));
        $form->textarea('token_jwt', __('Token jwt'));
        $form->text('remember_token', __('Remember token'));

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
