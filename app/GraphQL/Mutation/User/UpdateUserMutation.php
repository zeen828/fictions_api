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

class UpdateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => '更新會員'
    ];

    public function type(): type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'token' => [
                'name' => 'token',
                'type' => Type::string(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::string(),
            ],
            'nick_name' => [
                'name' => 'nick_name',
                'type' => Type::string(),
            ],
            'phone' => [
                'name' => 'phone',
                'type' => Type::string(),
            ],
            'sex' => [
                'name' => 'sex',
                'type' => Type::int(),
            ],
            'remarks' => [
                'name' => 'remarks',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $key = Config::get('jwt.secret');
        $userJwt = JWT::decode($args['token'], $key, array('HS256'));
        // print_r($userJwt);
        if(empty($userJwt)){
            // 失敗處理
            return null;
        }

        $user = User::active()->find($userJwt->id);
        if (!$user) {
            return null;
        }

        if(!empty($args['password'])){
            //$user->password = bcrypt($args['password']);
            $user->password = $args['password'];
        }

        if(!empty($args['nick_name'])){
            $user->nick_name = $args['nick_name'];
        }

        if(!empty($args['phone'])){
            $user->phone = $args['phone'];
        }

        if(!empty($args['sex'])){
            $user->sex = $args['sex'];
        }

        if(!empty($args['remarks'])){
            $user->remarks = $args['remarks'];
        }

        $user->save();
        return $user;
    }
}
/*
mutation user {
  updateUser(token: "", password: "123456", nick_name: "暱稱", phone: "13800000001", sex: 1, remarks: "備註") {
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