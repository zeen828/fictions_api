<?php
namespace App\GraphQL\Queries\Users;

use App\Model\Users\User;
//use Redis;

use Hash;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

// JWT
use \Firebase\JWT\JWT;
use Config;

class LoginQuery extends Query {

    protected $attributes = [
        'name'  => '登入',
        'description' => '登入會員',
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
            'account' => [
                'name' => 'account',
                'type' => Type::string(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::string(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        //$args['password'] = bcrypt($args['password']);
        // 電話取資料
        $query = User::active();
        $query->where('account', $args['account']);
        $user = $query->first();
        if (empty($user)) {
            // 查無資料
            return null;
        }
        //if (!Hash::check($args['password'], $user->password)) {
        if ($args['password'] != $user->password) {
            // 驗證失敗
            return null;
        }
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
{
  login(account: "auto000000000001", password: "123456") {
    id,
    token_jwt,
  }
}
*/