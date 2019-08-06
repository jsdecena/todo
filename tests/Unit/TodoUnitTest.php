<?php

namespace Tests\Unit;

use Tests\TestCase;
use Todo\Repositories\TodoRepository;
use Todo\Todo;

class TodoUnitTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_can_successfully_create_a_todo($data)
    {
        $todoRepository = new TodoRepository(new Todo);
        $todo = $todoRepository->create($data);

        $this->assertInstanceOf(Todo::class, $todo);

        $this->assertEquals($data['title'], $todo->title);
        $this->assertEquals($data['is_finished'], $todo->is_finished);
    }
}
