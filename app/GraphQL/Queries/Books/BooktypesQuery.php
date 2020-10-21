<?php
namespace App\GraphQL\Queries\Books;

use App\Model\Books\Booktype;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class BooktypesQuery extends Query {

    protected $attributes = [
        'name'  => '書籍類型清單',
        'description' => '書籍類型清單查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Booktype'));
    }

    public function args(): array
    {
        return [
            'ids' => [
                'name' => 'ids',
                'type' => Type::string(),
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int(),
                'defaultValue' => 1,
            ],
            'limit' => [
                'name' => 'limit',
                'type' => Type::int(),
                'defaultValue' => 50,
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = Booktype::has('books')->active();
        // 指定多筆查詢
        if (isset($args['ids'])) {
            // 讀Redis
            $redisKey = sprintf('book_type_byids%s_list%d_%d', md5($args['ids']), $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }

            // 查詢
            $in = explode(',', $args['ids']);
            $query->whereIn('id', $in);
            $query->orderBy(\DB::raw('FIND_IN_SET(id, "' . $args['ids'] . '"' . ")"));
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }

        // 普通多筆查詢
        // 讀Redis
        $redisKey = sprintf('book_type_list%d-%d', $args['page'], $args['limit']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
        return $redisVal;
    }
}
/*
{
  booktypes(ids: "1,9", page:1, limit:50) {
    typeId:id,
    name,
    description,
    sex,
    color,
    sort,
    books {
      bookId:id,
      name,
      description,
      typeId:tid,
      cover,
      tags,
      author,
    }
  }
}
{
  booktypes(page:1, limit:50) {
    typeId:id,
    name,
    description,
    sex,
    color,
    sort,
    books {
      bookId:id,
      name,
      description,
      typeId:tid,
      cover,
      tags,
      author,
    }
  }
}
*/