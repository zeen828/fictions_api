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
        // 讀Redis(配合後台可以清除調整不做分頁)
        // $redisKey = sprintf('domain_byspecies%d_list%d_%d', $args['species'], $args['page'], $args['limit']);
        $redisKey = sprintf('domain_byspecies%d_all', $args['species']);
        if ($redisVal = Redis::get($redisKey)) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Domain::active();
        // 指定多筆查詢
        if (isset($args['species'])) {
            $query->where('species', $args['species']);
        }
        // $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
        $redisVal = $query->get();

        // 寫Redis
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