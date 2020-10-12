<?php
namespace App\GraphQL\Types;

use App\Model\Books\Bookchapter;
use App\Model\Books\BookInfo;
use DB;
use Redis;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

use Illuminate\Support\Facades\Storage;

class ChapterType extends BaseType
{
    protected $attributes = [
        'name' => '書籍-章節',
        'description' => '書籍章節資料',
        'model' => Bookchapter::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'book_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '書籍ID',
            ],
            // 一對一
            'book' => [
                'type' => GraphQL::type('Book'),
                'description' => '一對一關聯-書籍',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '名稱',
            ],
            // 自訂欄位非DB欄位
            'content' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '小說內容',
                'resolve' => function($root, $args) {
                    // 自訂
                    $redisKey = sprintf('chapter_contentID%d', $root->id);
                    $redisVal = Redis::get($redisKey);
                    if(empty($redisVal)){
                        // OSS檔案
                        $path = str_replace('/Upload/', '', $root->oss_route);
                        //$redisVal = Storage::get($path);//設定檔預設寫法
                        $oss = Storage::disk('oss_txt');
                        $redisVal = $oss->read($path);
                        // 寫
                        Redis::set($redisKey, $redisVal, 'EX', 86400);// 60 * 60 * 24 一天
                    }
                    // 是否支付了
                    if($root->payment == 1){
                        // 刪減部分內容
                        $redisVal = str_limit(strip_tags($redisVal), 50, $end = '...');
                    }
                    return $redisVal;
                }
            ],
            'description' => [
                'type' => Type::string(),
                'description' => '描述',
            ],
            'next_description' => [
                'type' => Type::string(),
                'description' => '下章節描述',
            ],
            'oss_route' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'OSS路徑',
            ],
            'free' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '免費(0:停用,1:啟用)',
            ],
            'money' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '點數',
            ],
            // 自訂欄位非DB欄位
            'payment' => [
                'type' => Type::boolean(),
                'description' => '是否前往支付',
                'resolve' => function($root, $args) {
                    // 免付費才處理
                    if ($root->payment == false) {
                        $book_id = $root->book_id;
                        BookInfo::where('id', $book_id)->update(array(
                            'click_w' => DB::raw('click_w + 1'),
                            'click_m' => DB::raw('click_m + 1'),
                            'click_s'  => DB::raw('click_s + 1'),
                         ));
                    }
                    return $root->payment;
                }
            ],
            'sort' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '排序',
            ],
            // 自訂欄位非DB欄位
            // 上一個章節
            'previous' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '上一章節ID',
                'resolve' => function($root, $args) {
                    // 自訂
                    $data = Bookchapter::select('id', 'free', 'money')->where('book_id', $root->book_id)->where('sort', '<', $root->sort)->orderBy('sort', 'DESC')->first();
                    $id = 0;
                    if(!empty($data->id)){
                        $id = $data->id;
                    }
                    return $id;
                }
            ],
            // 自訂欄位非DB欄位
            // 下一個章節
            'next' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '下一章節ID',
                'resolve' => function($root, $args) {
                    // 自訂
                    $data = Bookchapter::select('id', 'free', 'money')->where('book_id', $root->book_id)->where('sort', '>', $root->sort)->orderBy('sort', 'ASC')->first();
                    $id = 0;
                    if(!empty($data->id)){
                        $id = $data->id;
                    }
                    return $id;
                }
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
