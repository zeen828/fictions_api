<?php
namespace App\GraphQL\Queries\Users;

use App\Model\Users\User;
//use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class UserQuery extends Query {

    protected $attributes = [
        'name'  => '會員',
        'description' => '會員查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('User');
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'account' => [
                'name' => 'account',
                'type' => Type::string(),
                //'rules' => ['required']// 檢查邏輯[必填]
            ],
            'token' => [
                'name' => 'token',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $query = User::active();
        if (isset($args['id'])) {
            $query->where('id', $args['id']);
        } elseif (isset($args['account'])) {
            $query->where('account', $args['account']);
        } elseif (isset($args['token'])) {
            $query->where('token_jwt', $args['token']);
        } else {
            return null;
        }
        //return $query->get();
        return $query->first();
    }
}
/*
{
  user(id: 1) {
    id,
    account,
    password,
    nick_name,
    phone,
    sex,
    points,
    channel_id,
    link_id,
    vip,
    vip_at,
    status,
    remarks,
    token_jwt,
  }
}
{
  user(account: "auto000000000001") {
    id,
    account,
    password,
    nick_name,
    phone,
    sex,
    points,
    channel_id,
    link_id,
    vip,
    vip_at,
    status,
    remarks,
    token_jwt,
  }
}
{
  user(token: "") {
    id,
    account,
    password,
    nick_name,
    phone,
    sex,
    points,
    channel_id,
    link_id,
    vip,
    vip_at,
    status,
    remarks,
    token_jwt,
  }
}
*/