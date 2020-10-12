<?php
namespace App\GraphQL\Types;

use App\Model\Orders\Order;
use App\Model\Orders\Payment;
use Redis;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class OrderType extends BaseType
{
    protected $attributes = [
        'name' => '訂單',
        'description' => '訂單詳細資料',
        'model' => Order::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '會員ID',
            ],
            'payment_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '支付ID',
            ],
            'order_sn' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '訂單',
            ],
            'price' => [
                'type' => Type::float(),
                'description' => '金額',
            ],
            'point_old' => [
                'type' => Type::int(),
                'description' => '儲點前',
            ],
            'points' => [
                'type' => Type::int(),
                'description' => '點數',
            ],
            'point_new' => [
                'type' => Type::int(),
                'description' => '儲點後',
            ],
            'vip' => [
                'type' => Type::int(),
                'description' => 'VIP(0:停用,1:啟用)',
            ],
            'vip_at_old' => [
                'type' => Type::string(),
                'description' => '原本VIP到期時間',
            ],
            'vip_day' => [
                'type' => Type::int(),
                'description' => 'VIP天數',
            ],
            'vip_at_new' => [
                'type' => Type::string(),
                'description' => '儲值後VIP到期時間',
            ],
            'transaction_sn' => [
                'type' => Type::string(),
                'description' => '交易訂單',
            ],
            'transaction_at' => [
                'type' => Type::string(),
                'description' => '交易完成時間',
            ],
            'app' => [
                'type' => Type::int(),
                'description' => 'APP(1:WAP,2:APP)',
            ],
            'channel_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '渠道',
            ],
            'link_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '推廣id',
            ],
            'callbackUrl' => [
                'type' => Type::string(),
                'description' => '支付返回',
            ],
            'sdk' => [
                'type' => Type::string(),
                'description' => 'SDK',
            ],
            'config' => [
                'type' => Type::string(),
                'description' => '支付商設定',
            ],
            // 自訂欄位非DB欄位
            'payurl' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '支付網址',
                'resolve' => function($root, $args) {
                    // Redis
                    $redisKey = sprintf('payment_ID%d', $root->payment_id);
                    if ($redisVal = Redis::get($redisKey)) {
                        $redisVal = unserialize($redisVal);
                    } else {
                        $redisVal = Payment::whereIn('status', [1, 2])->findOrFail($root->payment_id);
                        // 寫
                        Redis::set($redisKey, serialize($redisVal), 'EX', 3600);// 60 * 60 一小時
                    }
                    $payment = $redisVal;
                    // APP是否跳出呼叫支付(paymode0:不跳出APP,1:跳出APP)
                    $apk = json_decode($root->config);
                    $paymode = 0;
                    if(isset($apk->paymode) && $apk->paymode == 1){
                        $paymode = 1;
                    }
                    // 組合URL
                    $payurl = sprintf('%s/cartoon/pay/recharge?orderId=%s&fromSite=WT0&paySdk=%s&paymode=%s', $payment->domain, $root->order_sn, $root->sdk, $paymode);
                    $starpayurl = sprintf('%s?redirect=%s', $payment->domain, urlencode($payurl));
                    return $starpayurl;
                }
            ],
            'status' => [
                'type' => Type::int(),
                'description' => '狀態(0:未支付,1:支付成功,2:支付失敗)',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => '建立時間',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
