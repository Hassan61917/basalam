<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\VisitResource;
use App\Models\Visit;
use App\ModelServices\Social\VisitService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserVisitController extends AuthUserController
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
        $visits = $this->visitService->userVisits($this->authUser(), ["ads"]);
        return $this->ok($this->paginate($visits));
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit): JsonResponse
    {
        if ($visit->trashed()) {
            throw new NotFoundHttpException();
        }
        $visit->load("ads");
        return $this->ok($visit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit): JsonResponse
    {
        $visit->delete();
        return $this->deleted();
    }

    public function destroyAll(): JsonResponse
    {
        $this->visitService->destroyAllFor($this->authUser());
        return $this->deleted();
    }

}
