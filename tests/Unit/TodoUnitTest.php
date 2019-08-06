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
    public function it_can_delete_the_todo($data)
    {
        $created = factory(Todo::class)->create();

        $todoRepository = new TodoRepository($created);

        $update = $todoRepository->delete();

        $this->assertTrue($update);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_can_update_the_created_todo($data)
    {
        $created = factory(Todo::class)->create();

        $todoRepository = new TodoRepository($created);

        $update = $todoRepository->update($data);

        $this->assertTrue($update);

        $todo = $todoRepository->findById($created->id);

        $this->assertInstanceOf(Todo::class, $todo);

        $this->assertEquals($data['title'], $todo->title);
        $this->assertEquals($data['is_finished'], $todo->is_finished);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     */
    public function it_can_show_the_created_todo($data)
    {
        $created = factory(Todo::class)->create($data);

        $todoRepository = new TodoRepository($created);

        $todo = $todoRepository->findById($created->id);

        $this->assertInstanceOf(Todo::class, $todo);

        $this->assertEquals($data['title'], $todo->title);
        $this->assertEquals($data['is_finished'], $todo->is_finished);
    }

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
