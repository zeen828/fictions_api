<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class BaseType extends GraphQLType
{
    public function statusFields()
    {
        return [
            'code' => [
                'type' => Type::string(),
                'description' => '狀態碼',
                'resolve' => function($root, $args) {
                    $code = 0;
                    if (isset($root->code)) {
                        $code = $root->code;
                    }
                    if (isset($root['code'])) {
                        $code = $root['code'];
                    }
                    switch ($code) {
                        default:
                            return $code;
                            break;
                    }
                }
            ],
            'msg' => [
                'type' => Type::string(),
                'description' => '狀態訊息',
                'resolve' => function($root, $args) {
                    $code = 0;
                    if (isset($root->code)) {
                        $code = $root->code;
                    }
                    if (isset($root['code'])) {
                        $code = $root['code'];
                    }
                    switch ($code) {
                        case 200:
                            return '成功';
                            break;
                        case 401:
                            return '未授權';
                            break;
                        case 402:
                            return '需要付費';
                            break;
                        case 403:
                            return '禁止';
                            break;
                        case 404:
                            return '未找​​到';
                            break;
                        case 405:
                            return '不允許的方法';
                            break;
                        case 406:
                            return '不可接受';
                            break;
                        case 407:
                            return '代理服務器需要身份驗證';
                            break;
                        case 408:
                            return '請求超時';
                            break;
                        case 409:
                            return '衝突';
                            break;
                        case 410:
                            return '飄';
                            break;
                        case 411:
                            return '長度要求';
                            break;
                        case 412:
                            return '預處理失敗';
                            break;
                        case 413:
                            return '負載過大';
                            break;
                        case 414:
                            return '請求URI太長';
                            break;
                        case 415:
                            return '不支持的媒體類型';
                            break;
                        case 416:
                            return '請求範圍不符合';
                            break;
                        case 417:
                            return '期望失敗';
                            break;
                        default:
                            return '';
                            break;
                    }
                }
            ],
        ];
    }
}
