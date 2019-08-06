<?php

namespace Tests;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    /**
     * @return array
     */
    public function todoProvider()
    {
        $faker = Faker::create();

        return [
            [
                [
                    'title' => $faker->randomElement(['eat', 'run', 'sleep', 'jump', 'drink', 'play', 'sing']),
                    'is_finished' => '0'
                ]
            ]
        ];
    }
}
