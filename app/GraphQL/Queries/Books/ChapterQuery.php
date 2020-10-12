<?php
namespace App\GraphQL\Queries\Books;

use App\Model\Books\Bookchapter;
use App\Model\Users\User;
use App\Model\Books\BookUserRead;
use App\Model\Orders\PointLog;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use App\GraphQL\Types\BaseType;

// JWT
use \Firebase\JWT\JWT;
use Config;

class ChapterQuery extends Query {

    protected $attributes = [
        'name'  => '章節',
        'description' => '章節查詢',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('Chapter');
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'token' => [
                'name' => 'token',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // Redis
        $redisKey = sprintf('chapter_ID%d', $args['id']);
        if ($redisVal = Redis::get($redisKey)) {
            $redisVal = unserialize($redisVal);
        } else {
            // 取得章節資料
            $query = Bookchapter::active();
            $redisVal = $query->findOrFail($args['id']);
            // 寫
            Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
        }
        $chapter = $redisVal;

        // 自訂付費標籤(true:需要付錢,false:不需要付錢)
        $chapter->payment = true;
        // 免費的
        if ($chapter->free == 1) {
            $chapter->payment = false;
            return $chapter;
        }

        // 驗證會員扣點數
        //$args['token'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwiYWNjb3VudCI6ImF1dG8wMDAwMDAwMDAwMDEiLCJuaWNrX25hbWUiOm51bGwsInBob25lIjoiMTM4MDAwMDAwMDEiLCJzZXgiOjAsInZpcCI6MCwidmlwX2F0IjpudWxsfQ.YBS_gOiI5rVCcgnymoC15FTZUU7DzuVx1uCI8rDqO4k';
        if (isset($args['token']) && !empty($args['token'])) {
            // 解JWT取user.id
            $key = Config::get('jwt.secret');
            $userJwt = JWT::decode($args['token'], $key, array('HS256'));
            if (!empty($userJwt)) {
                // 取user資料
                $user = User::active()->find($userJwt->id);
                if (!empty($user)) {
                    // 閱讀&點數LOG紀錄
                    $userReadSave = [
                        'user_id' => $user->id,
                        'book_id' => $chapter->book_id,
                        'chapter_id' => $chapter->id,
                    ];
                    // 檢查閱讀紀錄 - 付過費
                    $pointlog = BookUserRead::where($userReadSave)->first();
                    if (!empty($pointlog)) {
                        $chapter->payment = false;
                        return $chapter;
                    }
                    // 判斷點數是否足夠
                    if ($user->points >= $chapter->money) {
                        // 閱讀紀錄
                        BookUserRead::create($userReadSave);
                        // 點數紀錄
                        $point_new = $user->points - $chapter->money;
                        $logSave = $userReadSave;
                        $logSave['event'] = 0;
                        $logSave['point_old'] = $user->points;
                        $logSave['point'] = $chapter->money;
                        $logSave['point_new'] = $point_new;
                        $logSave['remarks'] = '看书扣' . $chapter->money;
                        $logSave['status'] = 1;
                        // 扣點數
                        $user->points = $point_new;
                        $run = $user->save();
                        if ($run) {
                            // 成功扣點
                            $chapter->payment = false;
                            // 點數紀錄
                            PointLog::create($logSave);
                            return $chapter;
                        }
                    }
                }
            }
        }
        return $chapter;
    }
}
/*
{
  chapter(id:1280855, token: "") {
    chapterId:id,
    bookId:book_id,
    book {
      bookId:id,
      name,
    }
    name,
    content,
    description,
    free,
    money,
    payment,
    previous,
    next,
  }
}
{
  chapter(id:1612178, token: "") {
    chapterId:id,
    bookId:book_id,
    book {
      bookId:id,
      name,
    }
    name,
    content,
    description,
    free,
    money,
    payment,
    previous,
    next,
  }
}
*/