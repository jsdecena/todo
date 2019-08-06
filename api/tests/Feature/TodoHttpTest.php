<?php

namespace Tests\Feature;

use Tests\TestCase;
use Todo\Todo;

class TodoHttpTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_list_all_todo($data)
    {
        factory(Todo::class)->create($data);

        $this->get(route('todo.index'))
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_delete_the_todo($data)
    {
        $todo = $created = factory(Todo::class)->create($data);

        $this->delete(route('todo.destroy', $todo->id))
            ->assertStatus(202)
            ->assertJsonFragment(['message' => 'Delete successful!']);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_error_updating_the_created_todo_with_invalid_data_type($data)
    {
        $todo = $created = factory(Todo::class)->create($data);

        $this->put(route('todo.update', $todo->id), ['title' => null])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'We encountered an error when updating your Todo, please try again.']);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_updated_the_created_todo($data)
    {
        $todo = $created = factory(Todo::class)->create();

        $this->put(route('todo.update', $todo->id), $data)
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_should_throw_an_error_when_todo_is_missing()
    {
        $this->get(route('todo.show', 999))
            ->assertStatus(404)
            ->assertJsonFragment(['error' => 'Todo is not found.']);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_should_show_the_created_todo($data)
    {
        $todo = $created = factory(Todo::class)->create($data);

        $this->get(route('todo.show', $todo->id))
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

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
