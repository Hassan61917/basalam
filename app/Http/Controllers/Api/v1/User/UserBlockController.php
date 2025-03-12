<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserBlockRequest;
use App\Http\Resources\v1\BlockResource;
use App\Models\UserBlock;
use App\ModelServices\Social\BlockService;
use Illuminate\Http\JsonResponse;

class UserBlockController extends AuthUserController
{
    protected string $resource = BlockResource::class;

    public function __construct(
        private BlockService $blockService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $blocks = $this->blockService->getBlocksFor($this->authUser(), ["block"]);
        return $this->ok($this->paginate($blocks));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserBlockRequest $request): JsonResponse
    {
        $data = $request->validated();
        $block = $this->blockService->block($this->authUser(), $data);
        return $this->ok($block);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserBlock $block): JsonResponse
    {
        $block->load(["block"]);
        return $this->ok($block);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserBlockRequest $request, UserBlock $block): JsonResponse
    {
        $data = $request->validated();
        $block->update($data);
        return $this->ok($block);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserBlock $block): JsonResponse
    {
        $block->delete();
        return $this->deleted();
    }
}
