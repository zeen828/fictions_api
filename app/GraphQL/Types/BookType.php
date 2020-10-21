<?php
namespace App\GraphQL\Types;

use App\Model\Books\Bookinfo;
use App\Model\Books\Booktype as Booktypes;// 名稱衝突
use App\Model\Books\Bookchapter;
use Redis;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class BookType extends BaseType
{
    protected $attributes = [
        'name' => '書籍',
        'description' => '書籍詳細資料',
        'model' => Bookinfo::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '名稱',
                'resolve' => function($root, $args) {
                    return strtolower($root->name);
                }
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '描述',
            ],
            'author' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '作者',
            ],
            'tags' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '標籤',
            ],
            // 多對多
            'types' => [
                'type' => Type::listOf(GraphQL::type('Booktype')),
                'description' => '多對多關聯-書籍類型',
            ],
            'tid' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '分類',
            ],
            'cover' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '封面',
            ],
            'cover_h' => [
                'type' => Type::string(),
                'description' => '封面-橫',
            ],
            'size' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '字數',
            ],
            'nature' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '性質(1:男頻,2:女頻,3:中性)',
            ],
            'end' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '完結(0:連載,1:完結)',
            ],
            'open' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '完全上架(0:還有章節未開放,1:完全開放)',
            ],
            'free' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '免費(0:停用,1:啟用)',
            ],
            'recom' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '推薦(0:停用,1:啟用)',
            ],
            'recom_chapter_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '推薦章節',
            ],
            'vip' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'VIP專屬(0:普通,1:VIP專屬)',
            ],
            'search' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '搜尋(0:全戰搜,1:前台不可,2:後台不可,3:全站不可)',
            ],
            'click_w' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '周點擊',
            ],
            'click_m' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '月點擊',
            ],
            'click_s' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '總點擊',
            ],
            'click_o' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '外頭點擊擊',
            ],
            // 一對多
            'chapter' => [
                'type' => Type::listOf(GraphQL::type('Chapter')),
                //'type' => Type::nonNull(Type::string()),
                'description' => '一對多關聯-章節',
                'resolve' => function($root) {
                    // Redis
                    $redisKey = sprintf('chapter_bybookid%d_all', $root->id);
                    if ($redisVal = Redis::get($redisKey)) {
                        return unserialize($redisVal);
                    }
                    $redisVal = Bookchapter::active()->where('book_id', $root->id)->get();
                    // 寫
                    Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
                    return $redisVal;
                }
            ]
        ];

        return array_merge($fields, $this->statusFields());
    }
}
