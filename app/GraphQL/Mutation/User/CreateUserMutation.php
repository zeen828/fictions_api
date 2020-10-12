<?php
namespace App\GraphQL\Mutation\User;

use App\Model\Users\User;
//use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

// JWT
use \Firebase\JWT\JWT;
use Config;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => '新增會員'
    ];

    public function type(): type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'account' => [
                'name' => 'account',
                'type' => Type::nonNull(Type::string()),
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string()),
            ],
            'phone' => [
                'name' => 'phone',
                'type' => Type::string(),
            ],
            'app' => [
                'name' => 'app',
                'type' => Type::int(),
                'defaultValue' => 1,
            ],
            'channel_id' => [
                'name' => 'channel_id',
                'type' => Type::int(),
                'defaultValue' => 0,
            ],
            'link_id' => [
                'name' => 'link_id',
                'type' => Type::int(),
                'defaultValue' => 0,
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // 檢查是否存在
        $queryPk = User::where('account', $args['account'])->first();
        // 如果存在回NULL不執行
        if (!empty($queryPk)) {
            return null;
        }

        //$args['password'] = bcrypt($args['password']);
        $user = User::create($args);
        // 反查
        $user = User::active()->find($user->id);
        // 生成JWT
        $jwt_data = [
            'id' => $user->id,
            'account' => $user->account,
            'nick_name' => $user->nick_name,
            'phone' => $user->phone,
            'sex' => $user->sex,
            'vip' => $user->vip,
            'vip_at' => $user->vip_at,
        ];
        $key = Config::get('jwt.secret');
        $token = JWT::encode($jwt_data, $key);
        //$jwt_data['token'] = $token;
        $user->token_jwt = $token;
        $user->save();
        return $user;
    }
}
/*
mutation user {
  createUser(account: "auto000000000001", password: "123456", phone: "13800000001", channel_id: 0, link_id: 0) {
    id,
    account,
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