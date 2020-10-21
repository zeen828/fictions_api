<?php
namespace App\GraphQL\Queries\Promotes;

use App\Model\Promotes\ChannelApk;
use App\Model\Promotes\Channel;
use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DownloadQuery extends Query {

    protected $attributes = [
        'name'  => '下載APP',
        'description' => '下載APP',
    ];

    public function authorize($root, array $args, $context, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function type(): type
    {
        return GraphQL::type('Download');
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

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        // 讀Redis
        $redisKey = sprintf('channelapk_join_apk_bychannelid%d', $args['ChannelId']);
        if ($redisVal = Redis::get($redisKey)) {
            $redisVal = unserialize($redisVal);
            // 下載統計
            ChannelApk::where('id', $redisVal->id)->increment('download', 1);
            return $redisVal;
        }

        // 查詢
        // ChannelId:0 取預設渠道
        if ($args['ChannelId'] == 0) {
            $channel = Channel::active()->where('default', 1)->first();
            $args['ChannelId'] = $channel->id;
        }
        // $query = ChannelApk::active();
        // join作法
        $query = ChannelApk::where('t_channels_apk.status', 1)->where('t_apk.status', 1)->orderBy('t_channels_apk.id', 'DESC')
            ->select('t_channels_apk.*', 't_apk.version', 't_apk.app_version', 't_apk.description', 't_apk.apk')
            ->join('t_apk', 't_channels_apk.apk_id', '=', 't_apk.id');
        // 指定渠道
        if (isset($args['ChannelId'])) {
            $query->where('channel_id', $args['ChannelId']);
        }
        $redisVal = $query->first();
        // 寫Redis
        Redis::set($redisKey, serialize($redisVal), 'EX', 86400);// 60 * 60 * 24 一天
        // 下載統計
        ChannelApk::where('id', $redisVal->id)->increment('download', 1);
        return $redisVal;
    }
}
/*
{
  download(ChannelId:0) {
    id,
    version,
    app_version,
    description,
    apk,
    download,
  }
}
*/