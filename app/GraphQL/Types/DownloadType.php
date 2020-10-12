<?php
namespace App\GraphQL\Types;

use App\Model\Promotes\ChannelApk;
use App\Model\Domains\Domain;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class DownloadType extends BaseType
{
    protected $attributes = [
        'name' => '下載',
        'description' => '下載客製化',
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
                'type' => Type::string(),
                'description' => '版號',
            ],
            'app_version' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'APP版號',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '描述',
            ],
            'apk' => [
                'type' => Type::nonNull(Type::string()),
                'description' => '檔案',
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
