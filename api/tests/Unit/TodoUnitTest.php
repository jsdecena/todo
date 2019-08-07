<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Todo\Exceptions\CreateTodoErrorException;
use Todo\Exceptions\DeleteTodoErrorException;
use Todo\Exceptions\TodoNotFoundErrorException;
use Todo\Exceptions\UpdateTodoErrorException;
use Todo\Repositories\TodoRepository;
use Todo\Todo;

class TodoUnitTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider todoProvider
     */
    public function it_can_list_all_todo($data)
    {
        factory(Todo::class)->create($data);

        $todoRepository = new TodoRepository(new Todo);
        $lists = $todoRepository->queryBy();

        $this->assertInstanceOf(Builder::class, $lists);
        $this->assertCount(1, $lists->get());
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @throws DeleteTodoErrorException
     */
    public function it_can_delete_the_todo()
    {
        $created = factory(Todo::class)->create();

        $todoRepository = new TodoRepository($created);

        $update = $todoRepository->delete();

        $this->assertTrue($update);
    }

    /**
     * @test
     * @throws UpdateTodoErrorException
     */
    public function it_should_throw_an_exception_when_the_todo_being_updated_with_invalid_data_type()
    {
        $this->expectException(UpdateTodoErrorException::class);
        $this->expectExceptionCode(400);

        $created = factory(Todo::class)->create();
        $todoRepository = new TodoRepository($created);

        $todoRepository->update(['title' => null]);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     * @throws TodoNotFoundErrorException
     * @throws UpdateTodoErrorException
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
     * @throws TodoNotFoundErrorException
     */
    public function it_should_throw_an_exception_when_the_todo_is_not_found()
    {
        $this->expectException(TodoNotFoundErrorException::class);
        $this->expectExceptionCode(404);

        $todoRepository = new TodoRepository(new Todo);
        $todoRepository->findById(999);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     * @throws TodoNotFoundErrorException
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
     * @throws CreateTodoErrorException
     */
    public function it_should_throw_an_exception_when_the_required_field_is_not_passed()
    {
        $this->expectException(CreateTodoErrorException::class);
        $this->expectExceptionCode(400);

        $todoRepository = new TodoRepository(new Todo);
        $todoRepository->create([]);
    }

    /**
     * @test
     *
     * @dataProvider todoProvider
     * @param $data
     * @throws CreateTodoErrorException
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
