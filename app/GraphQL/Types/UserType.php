<?php
namespace App\GraphQL\Types;

use App\Model\Users\User;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class UserType extends BaseType
{
    protected $attributes = [
        'name' => '會員',
        'description' => '會員詳細資料',
        'model' => User::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'account' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '帳號',
            ],
            'password' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '密碼',
            ],
            'nick_name' => [
                'type' => Type::string(),
                'description' => '暱稱',
            ],
            'phone' => [
                'type' => Type::string(),
                'description' => '行動電話',
            ],
            'sex' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '性別(0:未知,1:男,2:女)',
            ],
            'points' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '點數',
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
            'vip' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'VIP(0:不是,1:是)',
            ],
            'vip_at' => [
                'type' => Type::string(),
                'description' => 'VIP到期時間',
            ],
            'status' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '狀態(0:停用,1:啟用)',
            ],
            'remarks' => [
                'type' => Type::string(),
                'description' => '備註',
            ],
            'token_jwt' => [
                'type' => Type::string(),
                'description' => 'JWT Token',
            ],
            'remember_token' => [
                'type' => Type::string(),
                'description' => 'Token',
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }
}
