<?php
namespace App\GraphQL\Types;

use App\Model\Rankings\Ranking;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class RankType extends BaseType
{
    protected $attributes = [
        'name' => '排行',
        'description' => '排行詳細資料',
        'model' => Ranking::class
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
            'book_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '書擊ID',
            ],
            'random_title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '隨機標題',
            ],
            'random_tag' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '隨機標籤',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
