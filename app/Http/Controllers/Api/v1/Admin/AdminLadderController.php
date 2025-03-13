<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminLadderRequest;
use App\Http\Resources\v1\LadderResource;
use App\Models\Ladder;
use App\ModelServices\Ads\LadderService;
use Illuminate\Http\JsonResponse;

class AdminLadderController extends Controller
{
    protected string $resource = LadderResource::class;

    public function __construct(
        private LadderService $ladderService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $ladders = $this->ladderService->getLadders(["category"])->withCount("orders");
        return $this->ok($this->paginate($ladders));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminLadderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $ladder = $this->ladderService->makeLadder($data);
        return $this->ok($ladder);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ladder $ladder): JsonResponse
    {
        $ladder->load("category", "orders")->loadCount("orders");
        return $this->ok($ladder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminLadderRequest $request, Ladder $ladder): JsonResponse
    {
        $data = $request->dontUnset()->validated();
        $ladder->update($data);
        return $this->ok($ladder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ladder $info): JsonResponse
    {
        $info->delete();
        return $this->deleted();
    }
}
