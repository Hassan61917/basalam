<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserQuestionRequest;
use App\Http\Resources\v1\QuestionResource;
use App\Models\Question;
use App\ModelServices\Shop\QuestionService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminQuestionController extends Controller
{
    protected string $resource = QuestionResource::class;

    public function __construct(
        private QuestionService $questionService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $questions = $this->questionService->getAll();
        return $this->ok($this->paginate($questions));
    }

    public function show(Question $question): JsonResponse
    {
        $question->load(["user", "service", "item"]);
        return $this->ok($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserQuestionRequest $request, Question $question): JsonResponse
    {
        $question->update($request->validated());
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
