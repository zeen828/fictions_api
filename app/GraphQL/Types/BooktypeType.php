<?php
namespace App\GraphQL\Types;

use App\Model\Books\Booktype;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class BooktypeType extends BaseType
{
    protected $attributes = [
        'name' => '書籍類型',
        'description' => '書籍類型詳細資料',
        'model' => Booktype::class
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
            'sex' => [
                'type' => Type::int(),
                'description' => '性別(0:男,1:女)',
            ],
            'color' => [
                'type' => Type::string(),
                'description' => '顏色',
            ],
            'sort' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '排序',
            ],
            // 多對多
            'books' => [
                'type' => Type::listOf(GraphQL::type('Book')),
                'description' => '多對多關聯-書籍',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
