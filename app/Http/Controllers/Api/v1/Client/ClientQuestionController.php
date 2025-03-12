<?php

namespace App\Http\Controllers\Api\v1\Client;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserQuestionRequest;
use App\Http\Resources\v1\QuestionResource;
use App\Models\Question;
use App\ModelServices\Shop\QuestionService;
use Illuminate\Http\JsonResponse;

class ClientQuestionController extends AuthUserController
{
    protected string $resource = QuestionResource::class;

    public function __construct(
        private QuestionService $questionService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $questions = $this->questionService->getQuestionsFor($this->authUser(), ["service", "item"]);
        return $this->ok($this->paginate($questions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserQuestionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $question = $this->questionService->makeQuestion($this->authUser(), $data);
        return $this->ok($question);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question): JsonResponse
    {
        $question->load("service", "item");
        return $this->ok($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserQuestionRequest $request, Question $question): JsonResponse
    {
        $data = $request->validated();
        $question->update($data);
        return $this->ok($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();
        return $this->deleted();
    }
}
