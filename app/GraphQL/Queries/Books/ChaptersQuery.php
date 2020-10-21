<?php
namespace App\GraphQL\Queries\Books;

use App\Model\Books\Bookchapter;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ChaptersQuery extends Query {

    protected $attributes = [
        'name'  => '章節清單',
        'description' => '章節清單查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Chapter'));
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'bookId' => [
                'name' => 'bookId',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int(),
                'defaultValue' => 1,
            ],
            'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
                'defaultValue' => 10,
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // 讀Redis
        $redisKey = sprintf('chapter_bybookid%d_list%d_%d', $args['bookId'], $args['page'], $args['limit']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Bookchapter::active();
        // 指定多筆查詢
        if (isset($args['bookId'])) {
            $query->where('book_id', $args['bookId']);
        }
        $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
        return $redisVal;
    }
}
/*
{
  chapters(bookId:74037, page:1, limit:5) {
    chapterId:id,
    bookId:book_id,
    name,
    description,
    free,
    money,
  }
}
*/