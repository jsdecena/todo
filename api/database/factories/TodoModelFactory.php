<?php

use Faker\Generator as Faker;
use Todo\Todo;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Todo::class, function (Faker $faker) {
    return [
        'title' => $faker->randomElement(['eat', 'run', 'sleep', 'jump', 'drink', 'play', 'sing']),
        'is_finished' => 0
    ];
});
