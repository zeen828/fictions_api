<?php
namespace App\GraphQL\Queries\Books;

use App\Model\Books\Bookinfo;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class BooksQuery extends Query {

    protected $attributes = [
        'name'  => '書籍清單',
        'description' => '書籍清單查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Book'));
    }

    public function args(): array
    {
        return [
            'ids' => [
                'name' => 'ids',
                'type' => Type::string(),
            ],
            'booktypeId' => [
              'name' => 'booktypeId',
              'type' => Type::int(),
            ],
            'tid' => [
                'name' => 'tid',
                'type' => Type::int(),
            ],
            'keyword' => [
                'name' => 'keyword',
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
                'defaultValue' => 10,
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = Bookinfo::active();
        // 指定多筆查詢
        if (isset($args['ids'])) {
            $in = explode(',', $args['ids']);
            $query->whereIn('id', $in);
            $query->orderBy(\DB::raw('FIND_IN_SET(id, "' . $args['ids'] . '"' . ")"));

            // Redis
            $redisKey = sprintf('book_byids_%s_list%d_%d', md5($args['ids']), $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }
        // 類型
        if (isset($args['booktypeId']) && $args['booktypeId'] != 0) {
            $booktypeId = $args['booktypeId'];
            $query->whereHas('types', function ($query) use ($booktypeId) {
                $query->where('t_booktype.id', $booktypeId);
            });

            // Redis
            $redisKey = sprintf('book_bybooktypeid_%d_list%d_%d', $args['booktypeId'], $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }
        // 類型
        if (isset($args['tid']) && $args['tid'] != 0) {
            $query->where('tid', $args['tid']);

            // Redis
            $redisKey = sprintf('book_bytid_%d_list%d_%d', $args['tid'], $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }
        // 搜尋
        if (isset($args['keyword'])) {
            $keyword = '%' . $args['keyword'] . '%';
            $query->where('name', 'like', $keyword);

            // Redis
            $redisKey = sprintf('book_bykey_%s_list%d_%d', md5($args['keyword']), $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }
        //return $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        // Redis
        $redisKey = sprintf('book_list%d_%d', $args['page'], $args['limit']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }
        $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
        return $redisVal;
    }
}
/*
{
  books(page:1, limit:5) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    author,
  }
}
{
  books(ids: "70004,74037", limit:50) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    author,
  }
}
{
  books(booktypeId: 0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    author,
  }
}
{
  books(tid: 0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    author,
  }
}
{
  books(keyword: "神秘", page:1, limit:5) {
    bookId:id,
    name,
    description,
    typeId:tid,
    cover,
    tags,
    author,
  }
}
*/