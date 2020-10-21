<?php
namespace App\GraphQL\Queries\Op;

use App\Model\Promotes\ChannelApk;
use App\Model\Promotes\Channel;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ChannelQuert extends Query {

    protected $attributes = [
        'name'  => '渠道',
        'description' => '渠道',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('Channel');
    }

    // 查詢條件
    public function args(): array
    {
        return [
            'ChannelId' => [
                'name' => 'ChannelId',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        // 讀Redis
        $redisKey = sprintf('channel_bychannelid%d', $args['ChannelId']);
        if ($redisVal = Redis::get($redisKey) && false) {
            return unserialize($redisVal);
        }

        // 查詢
        $query = Channel::active();
        // ChannelId:0 取預設渠道
        if ($args['ChannelId'] == 0) {
            $channel = $query->where('default', 1);
        } else {
            $channel = $query->where('id', $args['ChannelId']);
        }

        $redisVal = $query->first();
        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
        return $redisVal;
    }
}
/*
{
  channel(ChannelId: 0) {
    tags,
    imgs,
    books,
    download,
  }
}
*/