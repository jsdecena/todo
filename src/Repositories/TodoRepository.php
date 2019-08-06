<?php

namespace Todo\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Todo\Exceptions\CreateTodoErrorException;
use Todo\Exceptions\DeleteTodoErrorException;
use Todo\Exceptions\TodoNotFoundErrorException;
use Todo\Exceptions\UpdateTodoErrorException;
use Todo\Todo;

class TodoRepository
{
    /**
     * @var $model Todo
     */
    protected $model;

    /**
     * TodoRepository constructor.
     * @param Todo $todo
     */
    public function __construct(Todo $todo)
    {
        $this->model = $todo;
    }

    /**
     * Create the Todo
     *
     * @param array $data
     * @return Todo
     * @throws CreateTodoErrorException
     */
    public function create(array $data): Todo
    {
        try {
            return $this->model->create($data);
        } catch (QueryException $e) {
            throw new CreateTodoErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return Todo
     * @throws TodoNotFoundErrorException
     */
    public function findById(int $id): Todo
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new TodoNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws UpdateTodoErrorException
     */
    public function update(array $data): bool
    {
        try {
            return $this->model->update($data);
        } catch (QueryException $e) {
            throw new UpdateTodoErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws DeleteTodoErrorException
     */
    public function delete(): bool
    {
        try {
            return $this->model->delete();
        } catch (QueryException $e) {
            throw new DeleteTodoErrorException($e);
        }
    }
}
