<?php

declare(strict_types=1);

use example\Mutation\ExampleMutation;
use example\Query\ExampleQuery;
use example\Type\ExampleRelationType;
use example\Type\ExampleType;

return [

    // The prefix for routes
    'prefix' => 'graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class.'@query',

    // Any middleware for the graphql route group
    'middleware' => [],

    // Additional route group attributes
    //
    // Example:
    //
    // 'route_group_attributes' => ['guard' => 'api']
    //
    'route_group_attributes' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                // 'example_query' => ExampleQuery::class,
                // 會員
                'user' => App\GraphQL\Queries\Users\UserQuery::class,
                // 會員 >> 登入
                'login' => App\GraphQL\Queries\Users\LoginQuery::class,
                // 會員 >> 簽到
                'sign' => App\GraphQL\Queries\Users\SignQuery::class,
                // 排行
                'rank' => App\GraphQL\Queries\Rankings\RankQuery::class,
                'ranks' => App\GraphQL\Queries\Rankings\RanksQuery::class,
                // 書籍類型
                'booktypes' => App\GraphQL\Queries\Books\BooktypesQuery::class,
                // 書籍
                'book' => App\GraphQL\Queries\Books\BookQuery::class,
                'books' => App\GraphQL\Queries\Books\BooksQuery::class,
                // 章節
                'chapter' => App\GraphQL\Queries\Books\ChapterQuery::class,
                'chapters' => App\GraphQL\Queries\Books\ChaptersQuery::class,
                // 儲值金額
                'amounts' => App\GraphQL\Queries\Orders\AmountsQuery::class,
                // 點數紀錄
                'pointlogs' => App\GraphQL\Queries\Orders\PointlogsQuery::class,
                // 域名
                'domains' => App\GraphQL\Queries\Domains\DomainsQuery::class,
                // 下載
                'download' => App\GraphQL\Queries\Promotes\DownloadQuery::class,
            ],
            'mutation' => [
                // 'example_mutation'  => ExampleMutation::class,
                // 會員
                'automaticCreateUser' => App\GraphQL\Mutation\User\AutomaticCreateUserMutation::class,
                'createUser' => App\GraphQL\Mutation\User\CreateUserMutation::class,
                'updateUser' => App\GraphQL\Mutation\User\UpdateUserMutation::class,
                // 訂單
                'automaticCreateOrder' => App\GraphQL\Mutation\Order\AutomaticCreateOrderMutation::class,
                // LogsUsersAccess
                'createLogsUsersAccess' => App\GraphQL\Mutation\Analysis\CreateLogsUsersAccessMutation::class,
            ],
            'middleware' => [],
            'method' => ['get', 'post'],
        ],
    ],

    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        // 'example'           => ExampleType::class,
        // 'relation_example'  => ExampleRelationType::class,
        // \Rebing\GraphQL\Support\UploadType::class,
        // 會員
        'User' => App\GraphQL\Types\UserType::class,
        // 排行
        'Rank' => App\GraphQL\Types\RankType::class,
        // 書籍類型
        'Booktype' => App\GraphQL\Types\BooktypeType::class,
        // 書籍
        'Book' => App\GraphQL\Types\BookType::class,
        // 章節
        'Chapter' => App\GraphQL\Types\ChapterType::class,
        // 儲值金額
        'Amount' => App\GraphQL\Types\AmountType::class,
        // 支付商
        'Payment' => App\GraphQL\Types\PaymentType::class,
        // 訂單
        'Order' => App\GraphQL\Types\OrderType::class,
        // 點數紀錄
        'Pointlog' => App\GraphQL\Types\PointlogType::class,
        // 域名
        'Domain' => App\GraphQL\Types\DomainType::class,
        // 下載
        'Download' => App\GraphQL\Types\DownloadType::class,
        // LogsUsersAccess
        'LogsUsersAccess' => App\GraphQL\Types\LogsusersaccessType::class,
        // 其他
        'Other' => App\GraphQL\Types\OtherType::class,
    ],

    // The types will be loaded on demand. Default is to load all types on each request
    // Can increase performance on schemes with many types
    // Presupposes the config type key to match the type class name property
    'lazyload_types' => false,

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key' => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix' => '/graphiql',
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],

    /*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
    'defaultFieldResolver' => null,

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
