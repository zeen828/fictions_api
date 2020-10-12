<?php

namespace App\Admin\Controllers;

use App\Model\Rankings\Ranking;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RankingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '書籍::排行管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ranking());

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
            $filter->like('book_id', __('fictions.ranking.book_id'));
        });

        //规格选择器
        //$grid->selector(function (Grid\Tools\Selector $selector) {
            //$selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用', '2' => '備用',]);
        //});

        $grid->column('id', __('fictions.id'));
        $grid->column('name', __('fictions.ranking.name'));
        $grid->column('book_id', __('fictions.ranking.book_id'))->limit(20);
        $grid->column('random_title', __('fictions.ranking.random_title'))->limit(20);
        $grid->column('random_tag', __('fictions.ranking.random_tag'));
        $grid->column('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用'])->label(['0' => 'danger', '1' => 'success']);
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
        $show = new Show(Ranking::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('name', __('fictions.ranking.name'));
        $show->field('book_id', __('fictions.ranking.book_id'));
        $show->field('random_title', __('fictions.ranking.random_title'));
        $show->field('random_tag', __('fictions.ranking.random_tag'));
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
        $form = new Form(new Ranking());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->text('name', __('fictions.ranking.name'));
        $form->text('book_id', __('fictions.ranking.book_id'));
        $form->textarea('random_title', __('fictions.ranking.random_title'));
        $form->text('random_tag', __('fictions.ranking.random_tag'));
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用'])->default(1);

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
