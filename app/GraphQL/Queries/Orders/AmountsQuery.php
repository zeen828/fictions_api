<?php
namespace App\GraphQL\Queries\Orders;

use App\Model\Orders\Amount;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class AmountsQuery extends Query {

    protected $attributes = [
        'name'  => '儲值金額清單',
        'description' => '儲值金額清單查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Amount'));
    }

    // 查詢條件
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
        // 讀Redis(配合後台可以清除調整不做分頁)
        //$redisKey = sprintf('amount_list_%d_%d', $args['page'], $args['limit']);
        $redisKey = sprintf('amount_all');
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Amount::active();
        //$redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        $redisVal = $query->get();

        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
        return $redisVal;
    }
}
/*
{
  amounts(page:1, limit:50) {
    id,
    name,
    description,
    price,
    point_base,
    point_give,
    points,
    vip,
    vip_name,
    vip_day,
    is_default,
    payment {
      id,
      name,
      sdk,
      domain
    },
    test_payment {
      id,
      name,
      sdk,
      domain
    }
  }
}
*/