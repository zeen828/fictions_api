<?php
namespace App\GraphQL\Types;

use App\Model\Analysis\LogsUsersAccess;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class LogsusersaccessType extends BaseType
{
    protected $attributes = [
        'name' => '點擊紀錄',
        'description' => '點擊紀錄',
        'model' => LogsUsersAccess::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'mode' => [
                'type' => Type::int(),
                'description' => '模式(0:日活耀,1:每小時活耀)',
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
        ];

        return array_merge($fields, $this->statusFields());
    }
}
