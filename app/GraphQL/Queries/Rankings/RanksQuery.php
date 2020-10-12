<?php
namespace App\GraphQL\Queries\Rankings;

use App\Model\Rankings\Ranking;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class RanksQuery extends Query {

    protected $attributes = [
        'name'  => '排行清單',
        'description' => '排行清單查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Rank'));
    }

    public function args(): array
    {
        return [
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
        $query = Ranking::active();
        //return $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        // Redis
        $redisKey = sprintf('rank_list%d_%d', $args['page'], $args['limit']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }
        $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        // 寫
        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);
        return $redisVal;
    }
}
/*
{
  ranks(page:1, limit:50) {
    id,
    name,
    bookIds:book_id,
    random_title,
    random_tag,
  }
}
*/