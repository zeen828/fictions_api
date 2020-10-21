<?php
namespace App\GraphQL\Types;

use App\Model\Books\Bookchapter;
use App\Model\Domains\Domain;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class ChannelType extends BaseType
{
    protected $attributes = [
        'name' => '渠道',
        'description' => '渠道資料',
        'model' => ChannelApk::class
    ];

    public function fields(): array
    {
        $fields = [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => '主鍵',
            ],
            'version' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '版號',
            ],
            'app_version' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'APP版號',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => '描述',
            ],
            'apk' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '檔案',
            ],
            // 自訂欄位非DB欄位
            'tags' => [
                'type' => Type::listOf(Type::string()),
                'description' => '多對多關聯-書籍類型',
                'resolve' => function($root, $args) {
                    return array('標籤1', '標籤2');
                }
            ],
            // 自訂欄位非DB欄位
            'imgs' => [
                'type' => Type::listOf(Type::string()),
                'description' => '多對多關聯-書籍類型',
                'resolve' => function($root, $args) {
                    return array('標籤1', '標籤2');
                }
            ],
            // 自訂欄位非DB欄位
            'chapter' => [
                'type' => GraphQL::type('Chapter'),
                'description' => '多對多關聯-章節內容',
            ],
            // 自訂欄位非DB欄位
            'download' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '下載',
                'resolve' => function($root, $args) {
                    // 取域名
                    $domain = Domain::active()->where('species', '2')->first();
                    if (empty($domain)) {
                        return null;
                    }
                    $http = ($domain->ssl == 1)? 'https' : 'http';
                    $download = sprintf('%s://%s%s', $http, $domain->domain, $root->uri);
                    return $download;
                }
            ],
        ];

        return array_merge($fields, $this->statusFields());
    }

}
