<?php
namespace App\GraphQL\Types;

use App\Model\Orders\Payment;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class PaymentType extends BaseType
{
    protected $attributes = [
        'name' => '支付商',
        'description' => '支付商詳細資料',
        'model' => Payment::class
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
            'domain' => [
                'type' => Type::string(),
                'description' => '支付域名',
            ],
            'domain_call' => [
                'type' => Type::string(),
                'description' => '回調域名',
            ],
            'sdk' => [
                'type' => Type::string(),
                'description' => 'sdk',
            ],
            'sdk2' => [
                'type' => Type::string(),
                'description' => 'sdk2',
            ],
            'limit' => [
                'type' => Type::int(),
                'description' => '支付限額',
            ],
            'ratio' => [
                'type' => Type::float(),
                'description' => '贈送比',
            ],
            'client' => [
                'type' => Type::int(),
                'description' => '客戶端(0:全部,1:Wap,2:App)',
            ],
            'float' => [
                'type' => Type::string(),
                'description' => '浮動(0:停用,1:啟用)',
            ],
            'min' => [
                'type' => Type::int(),
                'description' => '最小金額',
            ],
            'max' => [
                'type' => Type::int(),
                'description' => '最大金額',
            ],
            'config' => [
                'type' => Type::string(),
                'description' => '額外設定',
            ],
            'status' => [
                'type' => Type::int(),
                'description' => '狀態(0:停用,1:啟用)',
            ],
            // 多對多
            'amount' => [
                'type' => Type::listOf(GraphQL::type('Amount')),
                'description' => '多對多關聯-支付金額',
            ]
        ];

        return array_merge($fields, $this->statusFields());
    }
}
