<?php
namespace App\GraphQL\Queries\Domains;

use App\Model\Domains\Domain;
use Redis;

use Hash;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DomainsQuery extends Query {

    protected $attributes = [
        'name'  => '簽到',
        'description' => '會員簽到',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Domain'));
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'species' => [
                'name' => 'species',
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

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $query = Domain::active();
        // 指定多筆查詢
        if (isset($args['species'])) {
            $query->where('species', $args['species']);
        }
        //return $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        // Redis
        $redisKey = sprintf('domain_byspecies%d_list%d_%d', $args['species'], $args['page'], $args['limit']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }
        $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        // 寫
        Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
        return $redisVal;
    }
}
/*
{
  domains(species: 1) {
    id,
    species,
    ssl,
    power,
    domain,
    remarks,
    cdn_del,
    status,
  }
}
*/