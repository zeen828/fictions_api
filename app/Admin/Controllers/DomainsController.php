<?php

namespace App\Admin\Controllers;

use App\Model\Domains\Domain;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DomainsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '域名管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Domain());

        //條件
        //$grid->model()->active();
        $grid->model()->orderBy('species', 'asc')->orderBy('status', 'asc')->orderBy('id', 'desc');

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
            $filter->like('domain', __('fictions.domain.domain'));
        });

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('species', __('fictions.domain.species'), ['1' => '動態主體', '2' => 'APK下載']);
            $selector->select('ssl', __('fictions.domain.ssl'), ['0' => '無', '1' => '加密']);
            $selector->select('power', __('fictions.domain.power'), ['0' => '一般', '1' => '高權']);
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用', '2' => '備用']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('species', __('fictions.domain.species'))->using(['1' => '動態主體', '2' => 'APK下載']);
        $grid->column('ssl', __('fictions.domain.ssl'))->using(['0' => '無', '1' => '加密']);
        $grid->column('power', __('fictions.domain.power'))->using(['0' => '一般', '1' => '高權']);
        $grid->column('domain', __('fictions.domain.domain'))->qrcode();
        $grid->column('remarks', __('fictions.domain.remarks'));
        $grid->column('cdn_del', __('fictions.domain.cdn_del'))->using(['0' => '刪除', '1' => '啟用']);
        $grid->column('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用', '2' => '備用'])->label(['0' => 'danger', '1' => 'success', '2' => 'info']);
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
        $show = new Show(Domain::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('species', __('fictions.domain.species'))->using(['1' => '動態主體', '2' => 'APK下載']);
        $show->field('ssl', __('fictions.domain.ssl'))->using(['0' => '無', '1' => '加密']);
        $show->field('power', __('fictions.domain.power'))->using(['0' => '一般', '1' => '高權']);
        $show->field('domain', __('fictions.domain.domain'));
        $show->field('remarks', __('fictions.domain.remarks'));
        $show->field('cdn_del', __('fictions.domain.cdn_del'))->using(['0' => '刪除', '1' => '啟用']);
        $show->field('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用', '2' => '備用']);
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
        $form = new Form(new Domain());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->select('species', __('fictions.domain.species'))->options(['1' => '動態主體', '2' => 'APK下載'])->default(1);
        $form->radio('ssl', __('fictions.domain.ssl'))->options(['0' => '無', '1' => '加密'])->default(0);
        $form->radio('power', __('fictions.domain.power'))->options(['0' => '一般', '1' => '高權'])->default(0);
        $form->text('domain', __('fictions.domain.domain'));
        $form->textarea('remarks', __('fictions.domain.remarks'));
        $form->radio('cdn_del', __('fictions.domain.cdn_del'))->options(['0' => '刪除', '1' => '啟用'])->default(0);
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用', '2' => '備用'])->default(1);

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
