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

class AutomaticCreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => '自動新增會員'
    ];

    public function type(): type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
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
        // 檢查一組可註冊帳號
        do{
            $args['account'] = sprintf('AUTO%s', str_random(12));
            $queryPk = User::where('account', $args['account'])->first();
        } while ( !empty($queryPk) );// true會繼續跑回圈,有值就在跑一圈跑到沒值
        // 註冊
        $args['password'] = str_random(6);
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
  automaticCreateUser(channel_id: 0, link_id: 0) {
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