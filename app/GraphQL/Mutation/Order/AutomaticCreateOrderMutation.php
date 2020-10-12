<?php
namespace App\GraphQL\Mutation\Order;

use App\Model\Orders\Order;
use App\Model\Orders\Amount;
//use App\Model\Orders\Payment;
//use Redis;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

// JWT
use \Firebase\JWT\JWT;
use Config;

class AutomaticCreateOrderMutation extends Mutation
{
    protected $attributes = [
        'name' => '自動新增會員'
    ];

    public function type(): type
    {
        return GraphQL::type('Order');
    }

    public function args(): array
    {
        return [
            'token' => [
                'name' => 'token',
                'type' => Type::string(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'amount_id' => [
                'name' => 'amount_id',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
            ],
            'payment_id' => [
                'name' => 'payment_id',
                'type' => Type::int(),
                'rules' => ['required'],// 檢查邏輯[必填]
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
        $dataSave = [];
        // 檢查一組可用訂單代碼
        do{
            $orderTag = env('ORDER_TAG', 'WT0');
            $dataSave['order_sn'] = sprintf('%s%08d%s', $orderTag, date('Ymd'), str_random(11));
            $queryPk = Order::where('order_sn', $dataSave['order_sn'])->first();
        } while ( !empty($queryPk) );// true會繼續跑回圈,有值就在跑一圈跑到沒值
        // JWT反解user_info
        $key = Config::get('jwt.secret');
        $userJwt = JWT::decode($args['token'], $key, array('HS256'));
        // print_r($userJwt);
        if(empty($userJwt)){
            // 失敗處理
            return null;
        }

        $dataSave['user_id'] = $userJwt->id;
        // 支付商資料
        $amount = Amount::active()->find($args['amount_id']);
        // print_r($amount);
        if(empty($amount)){
            // 失敗處理
            return null;
        }
        $dataSave['price'] = $amount->price;
        $dataSave['points'] = $amount->points;
        $dataSave['vip'] = $amount->vip;
        $dataSave['vip_day'] = $amount->vip_day;
        // 支付金額資料
        $payment = $amount->payment->find($args['payment_id']);
        // print_r($payment);
        if(empty($payment)){
            // 失敗處理
            return null;
        }
        // 白癡浮動機制腦袋裝屎
        if ($payment->float == 1) {
            $min = ($payment->min < 1)? 1: $payment->min;
            $max = ($payment->max > 30)? 30: $payment->max;
            $del = rand($min, $max);
            $dataSave['price'] = $amount->price - ( $del / 100);
        }
        $dataSave['payment_id'] = $payment->id;
        $dataSave['callbackUrl'] = $payment->domain_call;
        $dataSave['sdk'] = $payment->sdk;
        $dataSave['config'] = json_encode($payment->config);// model有處理過所以要跟這處理
        //print_r($dataSave);
        //APP預設1
        $dataSave['app'] = $args['app'];
        //渠道ID&推廣ID
        $dataSave['channel_id'] = $args['channel_id'];
        $dataSave['link_id'] = $args['link_id'];
        $order = Order::create($dataSave);
        return $order;
    }
}
/*
mutation order {
  automaticCreateOrder(token: "", amount_id: 1, payment_id: 1, app: 1, channel_id: 0, link_id: 0) {
    id,
    user_id,
    payment_id,
    order_sn,
    price,
    point_old,
    points,
    point_new,
    vip,
    vip_at_old,
    vip_day,
    vip_at_new,
    transaction_sn,
    transaction_at,
    app,
    channel_id,
    link_id,
    callbackUrl,
    sdk,
    config,
    payurl,
    status,
    created_at,
  }
}
*/