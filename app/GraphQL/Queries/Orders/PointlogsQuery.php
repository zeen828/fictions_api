<?php
namespace App\GraphQL\Queries\Orders;

use App\Model\Orders\PointLog;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

// JWT
use \Firebase\JWT\JWT;
use Config;

class PointlogsQuery extends Query {

    protected $attributes = [
        'name'  => '點數紀錄',
        'description' => '點數紀錄查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return Type::listOf(GraphQL::type('Pointlog'));
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

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['token']) && !empty($args['token'])) {
            // 解JWT取user.id
            $key = Config::get('jwt.secret');
            $userJwt = JWT::decode($args['token'], $key, array('HS256'));
            //print_r($userJwt);
            //exit();
            if(!empty($userJwt)){
                // 讀Redis
                $redisKey = sprintf('pointlog_byuserjwtid%d_list_%d_%d', $userJwt->id, $args['page'], $args['limit']);
                if ($redisVal = Redis::get($redisKey)) {
                    return unserialize($redisVal);
                }

                // 查詢 - 取user點數資料
                $query = PointLog::active();
                $query->where('user_id', $userJwt->id);
                $redisVal = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

                // 寫Redis
                Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
                return $redisVal;
            } else {
                return '';
            }
        }else{
            return '';
        }
        
    }
}
/*
{
  pointlogs(token: "", page:1, limit:5) {
    id,
    user_id,
    book_id,
    book {
      bookId:id,
      name,
    },
    chapter_id,
    chapter {
      chapterId:id,
      name,
    },
    point_old,
    point,
    point_new,
    remarks,
    status,
    created_at,
  }
}
*/