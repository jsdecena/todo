<?php

namespace App\Http\Controllers;

use Todo\Exceptions\CreateTodoErrorException;
use Todo\Repositories\TodoRepository;
use Todo\Requests\CreateTodoRequest;

class TodoApiController extends Controller
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * TodoApiController constructor.
     * @param TodoRepository $todoRepository
     */
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * Create the Todo via Http Request
     *
     * @param CreateTodoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTodoRequest $request)
    {
        try {
            $todo = $this->todoRepository->create($request->only('title', 'is_finished'));

            return response()->json([
                'data' => $todo
            ], 201);
        } catch (CreateTodoErrorException $e) {
            return response()->json([
                'error' => 'Error in creating a Todo.',
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }
}
