<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\VisitResource;
use App\Models\Visit;
use App\ModelServices\Social\VisitService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminVisitController extends Controller
{

    protected string $resource = VisitResource::class;

    public function __construct(
        private VisitService $visitService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $visits = $this->visitService->getVisits(["user"]);
        return $this->ok($this->paginate($visits));
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit): JsonResponse
    {
        $visit->load(["user", "visitable"]);
        return $this->ok($visit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit): JsonResponse
    {
        $visit->forceDelete();
        return $this->deleted();
    }

    public function destroyTrashed(): JsonResponse
    {
        Visit::onlyTrashed()->forceDelete();
        return $this->deleted();
    }
}
