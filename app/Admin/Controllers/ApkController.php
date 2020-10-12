<?php

namespace App\Admin\Controllers;

use App\Model\Promotes\Apk;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

// 檔案處理
use Storage;

class ApkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '推廣::客戶端管理';
    protected $apk_dir = 'tmpapk';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Apk());

        //條件
        //$grid->model()->active();
        $grid->model()->orderBy('id', 'desc');

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
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('version', __('fictions.apk.version'));
        });

        //规格选择器
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用']);
        });

        $grid->column('id', __('fictions.id'));
        $grid->column('version', __('fictions.apk.version'));
        $grid->column('app_version', __('fictions.apk.app_version'));
        $grid->column('description', __('fictions.apk.description'));
        $grid->column('apk', __('fictions.apk.apk'));
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
        $show = new Show(Apk::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('version', __('fictions.apk.version'));
        $show->field('app_version', __('fictions.apk.app_version'));
        $show->field('description', __('fictions.apk.description'));
        $show->field('apk', __('fictions.apk.apk'));
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
        $form = new Form(new Apk());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->text('version', __('fictions.apk.version'))->pattern('[0-9]{n}[.][0-9]{n}[.][0-9]{n}|[0-9]{n}[.][0-9]{n}')->required();
        $form->text('app_version', __('fictions.apk.app_version'))->pattern('[0-9]{n}[.][0-9]{n}[.][0-9]{n}|[0-9]{n}[.][0-9]{n}')->required();
        $form->textarea('description', __('fictions.apk.description'));
        $form->file('apk', __('fictions.apk.apk'))->move($this->apk_dir)->required();
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用'])->default(1);

        $form->footer(function ($footer) {
            //$footer->disableReset();// 去掉`重置`按钮
            //$footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        // 保存前回调
        // $form->saving(function (Form $form) {
        //     dump('保存前回调');
        //     dump($form);
        //     exit();
        // });

        // 保存后回调
        $form->saved(function (Form $form) {
            // dump('保存回调');
            $id = $form->model()->id;
            $apk = Apk::find($id);

            // 1.檔案搬移目錄結構
            $old_apk = $apk->apk;//舊檔案位置
            // 檔案是原始上傳結構才處理
            if (strpos ($old_apk, $this->apk_dir) !== false) {
                $filename = str_replace($this->apk_dir . '/', '', $old_apk);
                $new_apk = sprintf('admin/apk/%d/%s', $id, $filename);
                // 搬移檔案
                Storage::delete($new_apk);
                if (Storage::move('admin/' . $old_apk, $new_apk)) {
                    // $url = Storage::url($new_apk);
                    $apk->apk = $new_apk;
                    $apk->save();
                }
            }
        });

        return $form;
    }
}
