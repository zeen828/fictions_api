<?php
namespace App\GraphQL\Types;

use App\Model\Rankings\Ranking;
use Redis;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class RankType extends BaseType
{
    protected $attributes = [
        'name' => '排行',
        'description' => '排行詳細資料',
        'model' => Ranking::class
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
            ],
            'book_id' => [
                'type' => Type::string(),
                'description' => '書擊ID',
                'resolve' => function($root, $args) {
                    // Redis
                    $redisKey = sprintf('rank_books_byid%d', $root->id);
                    if ($redisVal = Redis::get($redisKey)) {
                        $redisVal = unserialize($redisVal);
                    } else {
                        $redisVal = $root->books->implode('id', ',');
                        // 寫
                        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
                    }
                    // 防呆如果沒有值用
                    if(empty($redisVal)){
                        return $root->book_id;
                    }
                    return $redisVal;
                }
            ],
            'random_title' => [
                'type' => Type::string(),
                'description' => '隨機標題',
            ],
            'random_tag' => [
                'type' => Type::string(),
                'description' => '隨機標籤',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
