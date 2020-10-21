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
            // 單獨條件
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
            // 共用條件
            // 性質(1:男頻,2:女頻,3:中性)
            'nature' => [
              'name' => 'nature',
              'type' => Type::int(),
              'defaultValue' => 0,
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
        $query = Bookinfo::with('types')->active();
        // 男女頻條件
        if ($args['nature']!= 0) {
            $query->where('nature', $args['nature']);
        }

        // 指定多筆查詢
        if (isset($args['ids'])) {
            // 讀Redis
            $redisKey = sprintf('book_byids%s_nature%d_list%d_%d', md5($args['ids']), $args['nature'], $args['page'], $args['limit']);
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

        // 類型
        if (isset($args['booktypeId']) && $args['booktypeId'] != 0) {
            // 讀Redis
            $redisKey = sprintf('book_bybooktypeid%d_nature%d_list%d_%d', $args['booktypeId'], $args['nature'], $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }

            // 查詢
            $booktypeId = $args['booktypeId'];
            $query->whereHas('types', function ($query) use ($booktypeId) {
                $query->where('t_booktype.id', $booktypeId);
            });
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }

        // 類型
        if (isset($args['tid']) && $args['tid'] != 0) {
            // 讀Redis
            $redisKey = sprintf('book_bytid%d_nature%d_list%d_%d', $args['tid'], $args['nature'], $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }

            // 查詢
            $query->where('tid', $args['tid']);
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }

        // 關鍵字搜尋
        if (isset($args['keyword'])) {
            // 讀Redis
            $redisKey = sprintf('book_bykey%s_nature%d_list%d_%d', md5($args['keyword']), $args['nature'], $args['page'], $args['limit']);
            if ($redisVal = Redis::get($redisKey)) {
                return unserialize($redisVal);
            }

            // 查詢
            $keyword = '%' . $args['keyword'] . '%';
            $query->where('search', '1')->where('name', 'like', $keyword);
            $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

            // 寫Redis
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
            return $redisVal;
        }

        // 普通多筆查詢
        // 讀Redis
        $redisKey = sprintf('book_nature%d_list%d_%d', $args['nature'], $args['page'], $args['limit']);
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
  books(nature:0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    author,
    tags,
    typeId:tid,
    types {
      id,
      name,
      description,
      sex,
      color,
    },
    cover,
    cover_h,
    size,
    vip,
    click_s,
    click_o,
  }
}
{
  books(ids: "70004,74037", nature:0, limit:50) {
    bookId:id,
    name,
    description,
    author,
    tags,
    types {
      id,
      name,
      description,
      sex,
      color,
    },
    typeId:tid,
    cover,
    cover_h,
    size,
    vip,
    click_s,
    click_o,
  }
}
{
  books(booktypeId: 0, nature:0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    author,
    tags,
    types {
      id,
      name,
      description,
      sex,
      color,
    },
    typeId:tid,
    cover,
    cover_h,
    size,
    vip,
    click_s,
    click_o,
  }
}
{
  books(tid: 0, nature:0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    author,
    tags,
    types {
      id,
      name,
      description,
      sex,
      color,
    },
    typeId:tid,
    cover,
    cover_h,
    size,
    vip,
    click_s,
    click_o,
  }
}
{
  books(keyword: "神秘", nature:0, page:1, limit:5) {
    bookId:id,
    name,
    description,
    author,
    tags,
    types {
      id,
      name,
      description,
      sex,
      color,
    },
    typeId:tid,
    cover,
    cover_h,
    size,
    vip,
    click_s,
    click_o,
  }
}
*/