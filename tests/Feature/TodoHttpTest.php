<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Todo\Exceptions\CreateTodoErrorException;
use Todo\Repositories\TodoRepository;
use Todo\Todo;

class TodoHttpTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_an_error_validation_when_required_field_is_not_passed()
    {
        $this->post(route('todo.store'), [])
            ->assertStatus(422)
            ->assertJsonFragment([
                'title' => [
                    'The title field is required.'
                ]
            ]);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_create_a_todo($data)
    {
        $this->post(route('todo.store'), $data)
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }
}
