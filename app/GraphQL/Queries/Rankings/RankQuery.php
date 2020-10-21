<?php
namespace App\GraphQL\Queries\Rankings;

use App\Model\Rankings\Ranking;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class RankQuery extends Query {

    protected $attributes = [
        'name'  => '排行',
        'description' => '排行查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('Rank');
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
        // 讀Redis
        $redisKey = sprintf('rank_byid%d', $args['id']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Ranking::with('books')->active();
        $redisVal = $query->findOrFail($args['id']);

        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
        return $redisVal;
    }
}
/*
{
  rank(id:1) {
    id,
    name,
    bookIds:book_id,
    random_title,
    random_tag,
  }
}
*/