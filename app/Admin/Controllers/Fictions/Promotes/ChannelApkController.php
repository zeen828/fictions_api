<?php

namespace App\Admin\Controllers\Fictions\Promotes;

use App\Model\Promotes\ChannelApk;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ChannelApkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '推廣::渠道包日誌';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ChannelApk());

        //條件
        //$grid->model()->active();
        $grid->model()->orderBy('id', 'desc');

        //隱藏功能
        $grid->disableCreateButton();//禁用创建按钮
        //$grid->disablePagination();//禁用分页条
        $grid->disableFilter();//禁用查询过滤器
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
        //$grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
        //});

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('channel_id', __('fictions.channelsapk.channel_id'));
        $grid->column('apk_id', __('fictions.channelsapk.apk_id'));
        $grid->column('uri', __('fictions.channelsapk.uri'));
        $grid->column('download', __('fictions.channelsapk.download'));
        $grid->column('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用'])->label(['0' => 'danger', '1' => 'success']);
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
        $show = new Show(ChannelApk::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('channel_id', __('fictions.channelsapk.channel_id'));
        $show->field('apk_id', __('fictions.channelsapk.apk_id'));
        $show->field('uri', __('fictions.channelsapk.uri'));
        $show->field('download', __('fictions.channelsapk.download'));
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
        $form = new Form(new ChannelApk());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            $tools->disableView();// 去掉`查看`按钮
        });

        $form->number('channel_id', __('fictions.channelsapk.channel_id'));
        $form->number('apk_id', __('fictions.channelsapk.apk_id'));
        $form->text('uri', __('fictions.channelsapk.uri'));
        //$form->number('download', __('fictions.channelsapk.download'));
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用'])->default(0);

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
