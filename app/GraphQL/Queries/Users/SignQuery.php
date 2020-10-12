<?php
namespace App\GraphQL\Queries\Users;

use App\Model\Users\User;
use App\Model\Orders\PointLog;
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

class SignQuery extends Query {

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
        return GraphQL::type('Other');
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'token' => [
                'name' => 'token',
                'type' => Type::string(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        //return null;
        //return ['code' => 401];
        // JWT反解user_info
        $key = Config::get('jwt.secret');
        $userJwt = JWT::decode($args['token'], $key, array('HS256'));
        // print_r($userJwt);
        if (empty($userJwt)) {
            // 失敗處理
            return ['code' => 401];
        }

        //
        $sign_at = date('Y-m-d');
        $point = 50;//簽到點數
        $logSave = [
            'user_id' => $userJwt->id,
            'remarks' => sprintf('签到加%d %s', $point, $sign_at),
        ];
        // 檢查簽到
        $pointlog = PointLog::where($logSave)->first();
        if (!empty($pointlog)) {
            return ['code' => 403];
        }

        // 檢查會員
        $user = User::active()->find($userJwt->id);
        if (!$user) {
            return ['code' => 406];
        }

        $point_new = $user->points + $point;
        $logSave['book_id'] = 0;
        $logSave['chapter_id'] = 0;
        $logSave['event'] = 1;
        $logSave['point_old'] = $user->points;
        $logSave['point'] = $point;
        $logSave['point_new'] = $point_new;
        $logSave['status'] = 1;
        // 加點數
        $user->points = $point_new;
        $run = $user->save();
        if (!$run) {
            return ['code' => 412];
        }
        // 點數紀錄
        PointLog::create($logSave);
        return ['code' => 200];
    }
}
/*
{
  sign(token: "") {
    code,
    msg,
  }
}
*/