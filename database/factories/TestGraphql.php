<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'type_id' => '1',
        'name' => $faker->name,
        'description' => 'description',
        'remember_token' => Str::random(10),
    ];
});
