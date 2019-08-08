<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Todo\Exceptions\CreateTodoErrorException;
use Todo\Exceptions\DeleteTodoErrorException;
use Todo\Exceptions\TodoNotFoundErrorException;
use Todo\Exceptions\UpdateTodoErrorException;
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
     * List all Todo
     *
     * @Todo Paginate
     *
     * @return JsonResponse
     */
    public function index()
    {
        $lists = $this->todoRepository->queryBy()->orderBy('id', 'desc')->get();

        return response()->json(['data' => $lists]);
    }

    /**
     * Create the Todo via Http Request
     *
     * @param CreateTodoRequest $request
     * @return JsonResponse
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

    /**
     * Show the Todo
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $todo = $this->todoRepository->findById($id);

            return response()->json([
                'data' => $todo
            ]);
        } catch (TodoNotFoundErrorException $e) {
            return response()->json([
                'error' => 'Todo is not found.',
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Update the Todo
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $todo = $this->todoRepository->findById($id);

            $todoRepository = new TodoRepository($todo);
            $todoRepository->update($request->only('title', 'is_finished'));

            return response()->json([
                'data' => $todo
            ]);
        } catch (TodoNotFoundErrorException $e) {
            return response()->json([
                'error' => 'Todo is not found.',
                'code' => $e->getCode()
            ], $e->getCode());
        } catch (UpdateTodoErrorException $e) {
            return response()->json([
                'error' => 'We encountered an error when updating your Todo, please try again.',
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Delete the Todo
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $todo = $this->todoRepository->findById($id);

            $todoRepository = new TodoRepository($todo);
            $todoRepository->delete();

            return response()->json([
                'message' => 'Delete successful!'
            ], 202);
        } catch (TodoNotFoundErrorException $e) {
            return response()->json([
                'error' => 'Todo is not found.',
                'code' => $e->getCode()
            ], $e->getCode());
        } catch (DeleteTodoErrorException $e) {
            return response()->json([
                'error' => 'We encountered an error when deleting your Todo, please try again.',
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Lookup for a domain
     *
     * @param Request $request
     * @return void
     */
    public function lookup(Request $request)
    {
        try {

            $base = 'https://domain-availability-api.whoisxmlapi.com';
            $domain = $this->remove_protocol($request->input('domain'));

            $segment = '/api/v1?apiKey=at_PUHH5fMhG6w8arMcCVEds2TiCeGok&domainName=' . $domain;
            $url = $base . $segment;

            $client = new Client;
            $response = $client->request('GET', $url);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param $url
     * @return mixed
     */
    private function remove_protocol($url) {
        foreach(['http://', 'https://'] as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
}
