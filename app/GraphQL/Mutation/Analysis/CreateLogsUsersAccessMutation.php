<?php
namespace App\GraphQL\Mutation\Analysis;

use App\Model\Analysis\LogsUsersAccess;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateLogsUsersAccessMutation extends Mutation
{
    protected $attributes = [
        'name' => '新增會員'
    ];

    public function type(): type
    {
        return GraphQL::type('LogsUsersAccess');
    }

    public function args(): array
    {
        return [
            'mode' => [
                'name' => 'mode',
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
        $log = LogsUsersAccess::create($args);
        if (!$log) {
            return ['code' => 403];
        }
        return ['code' => 200];
    }
}
/*
mutation logs {
  createLogsUsersAccess(mode: 0, app: 1, channel_id: 0, link_id: 0) {
    code,
    msg,
  }
}
*/