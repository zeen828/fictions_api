<?php
namespace App\GraphQL\Types;

use App\Model\Orders\Amount;
use App\Model\Orders\Payment;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class AmountType extends BaseType
{
    protected $attributes = [
        'name' => '儲值金額',
        'description' => '儲值金額詳細資料',
        'model' => Amount::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '名稱',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => '描述',
            ],
            'price' => [
                'type' => Type::int(),
                'description' => '金額',
            ],
            'point_base' => [
                'type' => Type::int(),
                'description' => '基本點',
            ],
            'point_give' => [
                'type' => Type::int(),
                'description' => '贈送點',
            ],
            'points' => [
                'type' => Type::int(),
                'description' => '總點數',
            ],
            'point_cash' => [
                'type' => Type::int(),
                'description' => '反利',
            ],
            'vip' => [
                'type' => Type::int(),
                'description' => 'VIP(0:停用,1:啟用)',
            ],
            'vip_name' => [
                'type' => Type::string(),
                'description' => 'VIP名稱',
            ],
            'vip_day' => [
                'type' => Type::int(),
                'description' => 'VIP天數',
            ],
            'sort' => [
                'type' => Type::int(),
                'description' => '排序',
            ],
            'is_default' => [
                'type' => Type::int(),
                'description' => '預設(0:停用,1:啟用)',
            ],
            'status' => [
                'type' => Type::int(),
                'description' => '狀態(0:停用,1:啟用)',
            ],
            // 多對多(用關聯無法篩選取消改自己查詢)
            'payment' => [
                'type' => Type::listOf(GraphQL::type('Payment')),
                'description' => '多對多關聯-支付商',
                'resolve' => function($root, $args) {
                    $payments = Payment::where('status', '1')->get();
                    return $payments;
                }
            ],
            'test_payment' => [
                'type' => Type::listOf(GraphQL::type('Payment')),
                'description' => '多對多關聯-支付商',
                'resolve' => function($root, $args) {
                    $payments = Payment::where('status', '2')->get();
                    return $payments;
                }
            ]
        ];

        return array_merge($fields, $this->statusFields());
    }
}
