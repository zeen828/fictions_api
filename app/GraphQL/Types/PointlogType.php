<?php
namespace App\GraphQL\Types;

use App\Model\Orders\PointLog;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class PointlogType extends BaseType
{
    protected $attributes = [
        'name' => '點數紀錄',
        'description' => '點數紀錄資料',
        'model' => PointLog::class
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
            'book_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '書籍ID',
            ],
            // 一對一
            'book' => [
                'type' => GraphQL::type('Book'),
                'description' => '一對一關聯-書籍',
            ],
            'chapter_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '章節ID',
            ],
            // 一對一
            'chapter' => [
                'type' => GraphQL::type('Chapter'),
                'description' => '一對一關聯-章節',
            ],
            'point_old' => [
                'type' => Type::int(),
                'description' => '儲點前',
            ],
            'point' => [
                'type' => Type::int(),
                'description' => '消耗點數',
            ],
            'point_new' => [
                'type' => Type::int(),
                'description' => '儲點後',
            ],
            'remarks' => [
                'type' => Type::string(),
                'description' => '備註',
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
