<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Types\BaseType;

class OtherType extends BaseType
{
    protected $attributes = [
        'name' => '其他',
        'description' => '其他類型客製化',
    ];

    public function fields(): array
    {
        $fields = [
        ];

        return array_merge($fields, $this->statusFields());
    }

}
