<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Resources\v1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserQuestionController extends ShopController
{
    protected string $resource = QuestionResource::class;
    protected ?string $ownerRelation = "shop";

    public function index(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $questions = $shop->questions();
        return $this->ok($this->paginate($questions));
    }

    public function show(Question $question): JsonResponse
    {
        $question->load("user", "product");
        return $this->ok($question);
    }

    public function answer(Request $request, Question $question): JsonResponse
    {
        $data = $request->validate([
            "answer" => "required"
        ]);
        $question->update($data);
        return $this->ok($question);
    }
}
