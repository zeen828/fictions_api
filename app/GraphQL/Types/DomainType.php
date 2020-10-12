<?php
namespace App\GraphQL\Types;

use App\Model\Domains\Domain;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class DomainType extends BaseType
{
    protected $attributes = [
        'name' => '域名',
        'description' => '域名詳細資料',
        'model' => Domain::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'species' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '種類(0:未知,1:動態主體)',
            ],
            'ssl' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ssl(0:無,1:有)',
            ],
            'power' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '高權(0:無,1:有)',
            ],
            'domain' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '域名',
            ],
            'remarks' => [
                'type' => Type::string(),
                'description' => '備註',
            ],
            'cdn_del' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'CDN(0:刪除,1:啟用)',
            ],
            'status' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '狀態(0:停用,1:啟用,2:備用)',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
