<?php

namespace Todo\Repositories;

use Illuminate\Database\QueryException;
use Todo\Exceptions\CreateTodoErrorException;
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
}
