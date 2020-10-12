<?php

namespace App\Admin\Controllers;

use App\Model\Books\Bookinfo;
use App\Model\Books\Booktype;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

// 讀設定檔
use Config;
// 讀檔案
use Illuminate\Http\File;
// 阿里雲OSS
use Illuminate\Support\Facades\Storage;

class BookinfoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '書籍::書籍管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bookinfo());

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
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用']);
        });

        $grid->column('id', __('fictions.id'));
        //$grid->column('mode', __('fictions.bookinfo.mode'));
        $grid->column('name', __('fictions.bookinfo.name'))->limit(20);
        $grid->column('description', __('fictions.bookinfo.description'))->limit(20);
        $grid->column('author', __('fictions.bookinfo.author'));
        //$grid->column('tags', __('fictions.bookinfo.tags'));
        //$grid->column('tid', __('fictions.bookinfo.tid'));
        $grid->column('cover', __('fictions.bookinfo.cover'))->image();
        //$grid->column('size', __('fictions.bookinfo.size'));
        //$grid->column('nature', __('fictions.bookinfo.nature'));
        //$grid->column('new_at', __('fictions.bookinfo.new_at'));
        //$grid->column('end', __('fictions.bookinfo.end'));
        //$grid->column('open', __('fictions.bookinfo.open'));
        $grid->column('free', __('fictions.bookinfo.free'))->bool(['0' => false, '1' => true]);
        //$grid->column('recom', __('fictions.bookinfo.recom'));
        //$grid->column('recom_chapter_id', __('fictions.bookinfo.recom_chapter_id'));
        //$grid->column('vip', __('fictions.bookinfo.vip'));
        //$grid->column('search', __('fictions.bookinfo.search'));
        $grid->column('click_w', __('fictions.bookinfo.click_w'));
        $grid->column('click_m', __('fictions.bookinfo.click_m'));
        $grid->column('click_s', __('fictions.bookinfo.click_s'));
        //$grid->column('gid', __('fictions.bookinfo.gid'));
        //$grid->column('index', __('fictions.bookinfo.index'));
        $grid->column('status', __('fictions.status'))->using(['0' => '停用', '1' => '啟用', '2' => '待審'])->label(['0' => 'danger', '1' => 'success', '2' => 'info']);
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
        $show = new Show(Bookinfo::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            //$tools->disableList();// 去掉`列表`按钮
            //$tools->disableEdit();// 去掉`編輯`按钮
            $tools->disableDelete();// 去掉`删除`按钮
        });

        $show->field('id', __('fictions.id'));
        //$show->field('mode', __('fictions.bookinfo.mode'));
        $show->field('name', __('fictions.bookinfo.name'));
        $show->field('description', __('fictions.bookinfo.description'));
        $show->field('author', __('fictions.bookinfo.author'));
        $show->field('tags', __('fictions.bookinfo.tags'));
        $show->field('tid', __('fictions.bookinfo.tid'));
        $show->field('cover', __('fictions.bookinfo.cover'));
        $show->field('size', __('fictions.bookinfo.size'));
        $show->field('nature', __('fictions.bookinfo.nature'));
        $show->field('new_at', __('fictions.bookinfo.new_at'));
        $show->field('end', __('fictions.bookinfo.end'));
        $show->field('open', __('fictions.bookinfo.open'));
        $show->field('free', __('fictions.bookinfo.free'));
        $show->field('recom', __('fictions.bookinfo.recom'));
        $show->field('recom_chapter_id', __('fictions.bookinfo.recom_chapter_id'));
        $show->field('vip', __('fictions.bookinfo.vip'));
        $show->field('search', __('fictions.bookinfo.search'));
        //$show->field('click_w', __('fictions.bookinfo.click_w'));
        //$show->field('click_m', __('fictions.bookinfo.click_m'));
        //$show->field('click_s', __('fictions.bookinfo.click_s'));
        //$show->field('gid', __('fictions.bookinfo.gid'));
        //$show->field('index', __('fictions.bookinfo.index'));
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
        $form = new Form(new Bookinfo());

        $form->tools(function (Form\Tools $tools) {
            //$tools->disableList();// 去掉`列表`按钮
            $tools->disableDelete();// 去掉`删除`按钮
            //$tools->disableView();// 去掉`查看`按钮
        });

        //$form->switch('mode', __('fictions.bookinfo.mode'));
        $form->text('name', __('fictions.bookinfo.name'));
        $form->textarea('description', __('fictions.bookinfo.description'));
        $form->text('author', __('fictions.bookinfo.author'));
        $form->text('tags', __('fictions.bookinfo.tags'));
        $form->select('tid', __('fictions.bookinfo.tid'))->options(Booktype::active()->pluck('name', 'id'));
        $form->image('cover', __('fictions.bookinfo.cover'))->move(env('FILE_ADMIN_BOOK_COVER_PATH', 'oss/cover'))->uniqueName();
        $form->number('size', __('fictions.bookinfo.size'))->default(0);;
        $form->radio('nature', __('fictions.bookinfo.nature'))->options(['0' => '男頻', '1' => '女頻', '2' => '中性'])->default(0);
        $form->datetime('new_at', __('fictions.bookinfo.new_at'))->default(date('Y-m-d H:i:s'));
        $form->radio('end', __('fictions.bookinfo.end'))->options(['0' => '連載', '1' => '完結'])->default(0);
        $form->radio('open', __('fictions.bookinfo.open'))->options(['0' => '還有章節未開放', '1' => '完全開放'])->default(0);
        $form->radio('free', __('fictions.bookinfo.free'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->radio('recom', __('fictions.bookinfo.recom'))->options(['0' => '停用', '1' => '啟用'])->default(0);
        $form->number('recom_chapter_id', __('fictions.bookinfo.recom_chapter_id'))->default(0);;
        $form->radio('vip', __('fictions.bookinfo.vip'))->options(['0' => '普通', '1' => 'VIP專屬'])->default(0);
        $form->radio('search', __('fictions.bookinfo.search'))->options(['0' => '全戰搜', '1' => '前台不可', '2' => '後台不可', '3' => '全站不可'])->default(0);
        //$form->number('click_w', __('fictions.bookinfo.click_w'));
        //$form->number('click_m', __('fictions.bookinfo.click_m'));
        //$form->number('click_s', __('fictions.bookinfo.click_s'));
        //$form->number('gid', __('fictions.bookinfo.gid'));
        //$form->number('index', __('fictions.bookinfo.index'));
        $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用', '2' => '待審'])->default(2);

        $form->footer(function ($footer) {
            //$footer->disableReset();// 去掉`重置`按钮
            //$footer->disableSubmit();// 去掉`提交`按钮
            $footer->disableViewCheck();// 去掉`查看`checkbox
            $footer->disableEditingCheck();// 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        // 在表单提交前调用
        //$form->submitted(function (Form $form) {
            //dump('在表单提交前调用');
            //dump($form);
            //exit();
        //});

        // 保存前回调
        //$form->saving(function (Form $form) {
            //dump('保存前回调');
            //dump($form);
            //exit();
        //});

        //保存后回调
        $form->saved(function (Form $form) {
            //dump('保存后回调');
            //dump($form);
            // 讀資料
            $id = $form->model()->id;
            $book = Bookinfo::find($id);

            // Redis 清除
            $redisKey = sprintf('book_ID%d', $id);
            Redis::expire($redisKey, 0);

            // 準備資料
            $oss_bucken = Config::get('filesystems.disks.oss_img.bucket');
            $oss_endpoint = Config::get('filesystems.disks.oss_img.endpoint');
            $upload_path = Config::get('filesystems.disks.admin.root');
            $file_path = $book->cover;
            $oss_path = sprintf('books/%s/cover', $book->id);
            // OSS上傳
            $oss = Storage::disk('oss_img');
            $oss_uri = $oss->putFile($oss_path, new File($upload_path . '/' . $file_path));
            if(!empty($oss_uri)){
                //https://cps-books-img.oss-cn-hongkong.aliyuncs.com/bookimg/15687.jpg
                $ossUrl = sprintf('https://%s.%s/%s', $oss_bucken, $oss_endpoint, $oss_uri);
                // 重新存檔
                $book->cover = $ossUrl;
                $book->save();
                // 刪除服務器檔案
                Storage::disk('admin')->delete($file_path);
            }
            //exit();
        });

        return $form;
    }
}
