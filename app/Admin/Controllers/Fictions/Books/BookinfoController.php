<?php

namespace App\Admin\Controllers\Fictions\Books;

use App\Model\Books\Bookinfo;
use App\Model\Books\Booktype;
use Redis;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

// 檔案處理
use Storage;
// 讀設定檔
use Config;
// 讀檔案
use Illuminate\Http\File;

class BookinfoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '書籍::書籍管理';
    protected $cover_dir = 'tmp/cover';
    protected $cover_h_dir = 'tmp/cover_h';

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
            $booktypes = Booktype::orderBy('sort', 'ASC')->get();
            $type_arr = [];
            //dump($booktypes);
            if(!$booktypes->isEmpty()){
                foreach ($booktypes as $booktype) {
                    $type_arr[$booktype->id] = $booktype->name;
                }
            }
            //$type_arr = [1 => "奇幻·玄幻", 2 => "武侠·仙侠", 3 => "都市·青春", 4 => "重生·穿越", 5 => "游戏·竞技", 6 => "科幻·灵异", 7 => "其他" ,8 => "乡村·激情", 9 => "都市·言情", 10 => "历史·军事", 11 => "领主贵族", 12 => "异术超能", 13 => "漫画·美图", 14 => "古风·言情"];
            //dump($type_arr);
            $selector->select('nature', __('fictions.bookinfo.nature'), ['1' => '男頻', '2' => '女頻', '3' => '中性']);
            $selector->select('types', '類型', $type_arr, function ($query, $value) {
                //dump($value);
                $query->whereHas('types', function ($query) use ($value) {
                    $query->whereIN('t_booktype.id', $value);
                });
            });
            $selector->select('end', __('fictions.bookinfo.end'), ['0' => '連載', '1' => '完結']);
            $selector->select('open', __('fictions.bookinfo.open'), ['0' => '還有章節未開放', '1' => '完全開放']);
            $selector->select('free', __('fictions.bookinfo.free'), ['0' => '停用', '1' => '啟用']);
            $selector->select('recom', __('fictions.bookinfo.recom'), ['0' => '停用', '1' => '啟用']);
            $selector->select('vip', __('fictions.bookinfo.vip'), ['0' => '普通', '1' => 'VIP專屬']);
            $selector->select('search', __('fictions.bookinfo.search'), ['0' => '全戰搜', '1' => '前台不可', '2' => '後台不可', '3' => '全站不可']);
            $selector->select('status', __('fictions.status'), ['0' => '停用', '1' => '啟用', '2' => '待審']);
        });

        $grid->column('id', __('fictions.id'));
        //$grid->column('mode', __('fictions.bookinfo.mode'));
        $grid->column('name', __('fictions.bookinfo.name'))->limit(20);
        $grid->column('description', __('fictions.bookinfo.description'))->limit(20)->width(300);
        $grid->column('author', __('fictions.bookinfo.author'));
        //$grid->column('tags', __('fictions.bookinfo.tags'));
        //$grid->column('tid', __('fictions.bookinfo.tid'));
        // 多對多
        $grid->column('types')->display(function ($types) {
            $types = array_map(function ($types) {
                return "<span class='label label-success'>{$types['name']}</span>";
            }, $types);
            return join('&nbsp;', $types);
        });
        $grid->column('cover', __('fictions.bookinfo.cover'))->image();
        $grid->column('cover_h', __('fictions.bookinfo.cover_h'))->image();
        //$grid->column('size', __('fictions.bookinfo.size'));
        //$grid->column('nature', __('fictions.bookinfo.nature'));
        //$grid->column('new_at', __('fictions.bookinfo.new_at'));
        $grid->column('end', __('fictions.bookinfo.end'))->bool(['0' => false, '1' => true]);
        //$grid->column('open', __('fictions.bookinfo.open'));
        $grid->column('free', __('fictions.bookinfo.free'))->bool(['0' => false, '1' => true]);
        //$grid->column('recom', __('fictions.bookinfo.recom'));
        //$grid->column('recom_chapter_id', __('fictions.bookinfo.recom_chapter_id'));
        //$grid->column('vip', __('fictions.bookinfo.vip'));
        //$grid->column('search', __('fictions.bookinfo.search'));
        $grid->column('click_w', __('fictions.bookinfo.click_w'));
        $grid->column('click_m', __('fictions.bookinfo.click_m'));
        $grid->column('click_s', __('fictions.bookinfo.click_s'))->sortable();
        $grid->column('click_o', __('fictions.bookinfo.click_o'))->sortable();
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

        $form->column(1/2, function ($form) {
            //$form->switch('mode', __('fictions.bookinfo.mode'));
            $form->text('name', __('fictions.bookinfo.name'));
            $form->textarea('description', __('fictions.bookinfo.description'));
            $form->text('author', __('fictions.bookinfo.author'));
            $form->text('tags', __('fictions.bookinfo.tags'));
            // $form->select('tid', __('fictions.bookinfo.tid'))->options(Booktype::active()->pluck('name', 'id'));
            $form->multipleSelect('types', '類型')->options(Booktype::active()->pluck('name', 'id'));
            $form->image('cover', __('fictions.bookinfo.cover'))->move($this->cover_dir)->uniqueName();
            $form->image('cover_h', __('fictions.bookinfo.cover_h'))->move($this->cover_h_dir)->uniqueName();
        });
        $form->column(1/2, function ($form) {
            $form->text('size', __('fictions.bookinfo.size'))->default(0);
            $form->radio('nature', __('fictions.bookinfo.nature'))->options(['1' => '男頻', '2' => '女頻', '3' => '中性'])->default(0);
            $form->datetime('new_at', __('fictions.bookinfo.new_at'))->default(date('Y-m-d H:i:s'));
            $form->radio('end', __('fictions.bookinfo.end'))->options(['0' => '連載', '1' => '完結'])->default(0);
            $form->radio('open', __('fictions.bookinfo.open'))->options(['0' => '還有章節未開放', '1' => '完全開放'])->default(0);
            $form->radio('free', __('fictions.bookinfo.free'))->options(['0' => '停用', '1' => '啟用'])->default(0);
            $form->radio('recom', __('fictions.bookinfo.recom'))->options(['0' => '停用', '1' => '啟用'])->default(0);
            $form->text('recom_chapter_id', __('fictions.bookinfo.recom_chapter_id'))->default(0);
            $form->radio('vip', __('fictions.bookinfo.vip'))->options(['0' => '普通', '1' => 'VIP專屬'])->default(0);
            $form->radio('search', __('fictions.bookinfo.search'))->options(['0' => '全戰搜', '1' => '前台不可', '2' => '後台不可', '3' => '全站不可'])->default(0);
            //$form->number('click_w', __('fictions.bookinfo.click_w'));
            //$form->number('click_m', __('fictions.bookinfo.click_m'));
            //$form->number('click_s', __('fictions.bookinfo.click_s'));
            //$form->number('gid', __('fictions.bookinfo.gid'));
            //$form->number('index', __('fictions.bookinfo.index'));
            $form->radio('status', __('fictions.status'))->options(['0' => '停用', '1' => '啟用', '2' => '待審'])->default(2);
        });

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
            //dump($form->model());
            //exit();
            // 讀資料
            $id = $form->model()->id;
            $book = Bookinfo::find($id);
            //dump($book);
            //exit();

            // 封面上傳OSS
            $oss_bucken = Config::get('filesystems.disks.oss_img.bucket');
            $oss_endpoint = Config::get('filesystems.disks.oss_img.endpoint');
            $upload_path = Config::get('filesystems.disks.admin.root');
            $filePath = 'books/empty.jpg';

            // 檢查檔案是否為OSS網址格式
            if (!starts_with($book->cover, 'http://') || !starts_with($book->cover, 'https://')) {
                dump('wj62;3');
                $file_path = $book->cover;
                $oss_path = sprintf('books/%s/cover', $book->id);
                // 檔案資訊
                $filePath = $upload_path . '/' . $file_path;
                dump($filePath);
                // OSS上傳
                $oss_uri = Storage::disk('oss_img')->putFile($oss_path, new File($filePath));
                if(!empty($oss_uri)){
                    //https://cps-books-img.oss-cn-hongkong.aliyuncs.com/bookimg/15687.jpg
                    $ossUrl = sprintf('https://%s.%s/%s', $oss_bucken, $oss_endpoint, $oss_uri);
                    // 重新存檔
                    $book->cover = $ossUrl;
                    $book->save();
                    // 刪除服務器檔案
                    Storage::disk('admin')->delete($file_path);
                }
            }
            exit();

            $filePath = 'books/empty.jpg';
            // 檢查檔案是否為OSS網址格式
            if (!starts_with($book->cover_h, 'http://') || !starts_with($book->cover_h, 'https://')) {
                dump('wj62;3OOXX');
                $file_path = $book->cover_h;
                $oss_path = sprintf('books/%s/cover_h', $book->id);
                // 檔案資訊
                $filePath = $upload_path . '/' . $file_path;
                // OSS上傳
                $oss_uri = Storage::disk('oss_img')->putFile($oss_path, new File($filePath));
                if(!empty($oss_uri)){
                    //https://cps-books-img.oss-cn-hongkong.aliyuncs.com/bookimg/15687.jpg
                    $ossUrl = sprintf('https://%s.%s/%s', $oss_bucken, $oss_endpoint, $oss_uri);
                    // 重新存檔
                    $book->cover_h = $ossUrl;
                    $book->save();
                    // 刪除服務器檔案
                    Storage::disk('admin')->delete($file_path);
                }
            }

            // Redis 清除
            $redisKey = sprintf('book_byid%d', $id);
            Redis::expire($redisKey, 0);
        });

        return $form;
    }
}
