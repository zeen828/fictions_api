<?php
namespace App\GraphQL\Queries\Books;

use App\Model\Books\Bookinfo;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class BookQuery extends Query {

    protected $attributes = [
        'name'  => '書籍',
        'description' => '書籍查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('Book');
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // Redis
        $redisKey = sprintf('book_ID%d', $args['id']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Bookinfo::active();
        $query->where('id', $args['id']);
        $redisVal = $query->first();

        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
        return $redisVal;
    }
}
/*
{
  book(id:74037) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    types {
      id,
      name,
      description,
      sex,
      color,
    }
    author,
    chapter {
      chapterId:id,
      bookId:book_id,
      name,
      description,
      free,
      money,
      payment,
      sort,
      previous,
      next,
    }
  }
}
*/