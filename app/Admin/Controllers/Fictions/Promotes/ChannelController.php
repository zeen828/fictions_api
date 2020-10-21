<?php

namespace App\Admin\Controllers\Fictions\Promotes;

use App\Model\Promotes\Channel;
use App\Model\Promotes\Apk;
use App\Model\Promotes\ChannelApk;
use App\Model\Books\Bookinfo;
use App\Model\Books\Bookchapter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Widgets\Form as webFrom;

//use App\Admin\Actions\Channels\Package;
use App\Admin\Extensions\Channels\CheckRow;

class ChannelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '推廣::渠道管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Channel());

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

        //工具列
        $grid->tools(function (Grid\Tools $tools) {
            //$tools->append(new Package());
            $tools->append(new CheckRow(0));
        });

        //禁用行操作各別項目
        $grid->actions(function ($actions) {
            $actions->disableView();// 去掉查看
            //$actions->disableEdit();// 去掉编辑
            $actions->disableDelete();// 去掉删除
            // append一个操作
            //$actions->append('<a href=""><i class="fa fa-eye"></i></a>');
            // 添加操作
            $actions->append(new CheckRow($actions->getKey()));
            //https://laravel-admin.org/docs/zh/1.x/model-grid-actions
        });

        //篩選
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', __('fictions.channel.name'));
        });

        //表頭(规格选择器二選一)
        $grid->header(function ($query) {
            $form = new webFrom();
            return $form->select('apk_version', '客戶端')->options(Apk::active()->get()->pluck('version', 'id'));
        });

        //规格选择器(表頭二選一)
        // $grid->selector(function (Grid\Tools\Selector $selector) {
        //     $selector->select('mode', __('fictions.channel.mode'), ['0' => '首頁', '1' => '書籍', '2' => '章節', '3' => '自訂']);
        //     $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用']);
        // });

        //表尾
        // $grid->footer(function ($query) {
        //     return 'footer'; 
        // });

        $grid->column('id', __('fictions.id'));
        $grid->column('name', __('fictions.channel.name'));
        // $grid->column('description', __('fictions.channel.description'));
        $grid->column('mode', __('fictions.channel.mode'))->using(['0' => '首頁', '1' => '書籍', '2' => '章節', '3' => '自訂']);
        $grid->column('book.name.', __('fictions.channel.book_id'));
        $grid->column('chapter.name', __('fictions.channel.chapter_id'));
        // 不存在的`full_name`字段
        $grid->column('推廣包')->display(function ($title, $column) {
            //dump($title);
            //dump($column);
            //dump($this);
            $id = $this->id;
            //dump($id);
            //return sprintf('http://xxx/api/v1/ajax/createzip/%d/1', $id);
            return url("/api/v1/ajax/createzip/{$id}/1");
        })->downloadable();
        // $grid->column('book_id', __('fictions.channel.book_id'));
        // $grid->column('chapter_id', __('fictions.channel.chapter_id'));
        // $grid->column('url', __('fictions.channel.url'));
        // $grid->column('prefix', __('fictions.channel.prefix'));
        // $grid->column('wap_user_reg', __('fictions.channel.wap_user_reg'));
        // $grid->column('app_user_reg', __('fictions.channel.app_user_reg'));
        // $grid->column('wap_recharge', __('fictions.channel.wap_recharge'));
        // $grid->column('app_recharge', __('fictions.channel.app_recharge'));
        // $grid->column('wap_recharge_m', __('fictions.channel.wap_recharge_m'));
        // $grid->column('app_recharge_m', __('fictions.channel.app_recharge_m'));
        // $grid->column('wap_recharge_d', __('fictions.channel.wap_recharge_d'));
        // $grid->column('app_recharge_d', __('fictions.channel.app_recharge_d'));
        // $grid->column('download', __('fictions.channel.download'));
        $grid->column('for', __('fictions.channel.for'))->bool(['0' => false, '1' => true]);
        $grid->column('default', __('fictions.channel.default'))->using(['0' => '停用', '1' => '啟用'])->label(['0' => 'danger', '1' => 'success']);
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
        $show = new Show(Channel::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        $show->field('name', __('fictions.channel.name'));
        $show->field('description', __('fictions.channel.description'));
        $show->field('mode', __('fictions.channel.mode'))->using(['0' => '首頁', '1' => '書籍', '2' => '章節', '3' => '自訂']);
        $show->field('book_id', __('fictions.channel.book_id'));
        $show->field('chapter_id', __('fictions.channel.chapter_id'));
        $show->field('url', __('fictions.channel.url'));
        $show->field('prefix', __('fictions.channel.prefix'));
        // $show->field('wap_user_reg', __('fictions.channel.wap_user_reg'));
        // $show->field('app_user_reg', __('fictions.channel.app_user_reg'));
        // $show->field('wap_recharge', __('fictions.channel.wap_recharge'));
        // $show->field('app_recharge', __('fictions.channel.app_recharge'));
        // $show->field('wap_recharge_m', __('fictions.channel.wap_recharge_m'));
        // $show->field('app_recharge_m', __('fictions.channel.app_recharge_m'));
        // $show->field('wap_recharge_d', __('fictions.channel.wap_recharge_d'));
        // $show->field('app_recharge_d', __('fictions.channel.app_recharge_d'));
        // $show->field('download', __('fictions.channel.download'));
        $show->field('for', __('fictions.channel.for'));
        $show->field('default', __('fictions.channel.default'));
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
        $form = new Form(new Channel());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        $form->text('name', __('fictions.channel.name'))->required();
        $form->textarea('description', __('fictions.channel.description'));
        // 表單連動
        $form->radio('mode', __('fictions.channel.mode'))->options(['0' => '首頁', '1' => '書籍', '2' => '章節', '3' => '自訂'])->required()
        ->when(0, function (Form $form) { 
        })->when(1, function (Form $form) { 
            // 連動下拉選項
            $form->select('book_id', __('fictions.channel.book_id'))->options(Bookinfo::active()->get()->pluck('name', 'id'));
        })->when(2, function (Form $form) { 
            // 連動下拉選項
            $form->select('book_id', __('fictions.channel.book_id'))->options(Bookinfo::active()->get()->pluck('name', 'id'))->load('chapter_id', '/api/v1/admin/ajax/channel/options');
            $form->select('chapter_id', __('fictions.channel.chapter_id'))->options(function ($id) {
                $chapter = Bookchapter::find($id);
                if ($chapter) {
                    return [$chapter->id => $chapter->name];
                }
            });
        })->when(3, function (Form $form) { 
            $form->url('url', __('fictions.channel.url'));
        });
        $form->text('prefix', __('fictions.channel.prefix'));
        $form->radio('for', __('fictions.channel.for'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->radio('default', __('fictions.channel.default'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用'])->default(1);

        $form->footer(function ($footer) {
            //$footer->disableReset();// 去掉`重置`按钮
            //$footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        // 保存后回调
        $form->saved(function (Form $form) {
            // dump('保存回调');
            $id = $form->model()->id;
            $channel = Channel::find($id);

            // 預設渠道只能一個
            if ($channel->default == 1) {
                Channel::where('id', '!=', $id)->update(['default' => 0]);
            }
        });

        return $form;
    }
}
