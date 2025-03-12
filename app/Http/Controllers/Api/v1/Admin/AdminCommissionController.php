<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Exceptions\ModelException;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminCommissionRequest;
use App\Http\Resources\v1\CommissionResource;
use App\Models\Commission;
use App\ModelServices\Financial\CommissionService;
use Illuminate\Http\JsonResponse;

class AdminCommissionController extends Controller
{
    protected string $resource = CommissionResource::class;

    public function __construct(
        private CommissionService $commissionService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $commissions = $this->commissionService->getAll(["category"]);
        return $this->ok($this->paginate($commissions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminCommissionRequest $request)
    {
        $data = $request->validated();
        $commission = $this->commissionService->make($data);
        $commission->load("category");
        return $commission;
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission): JsonResponse
    {
        $commission->load("category");
        return $this->ok($commission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminCommissionRequest $request, Commission $commission): JsonResponse
    {
        $data = $request->validated();
        $commission->update($data);
        return $this->ok($commission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission): JsonResponse
    {
        if (!$commission->isExpired()) {
            throw new ModelException("un expired commission can not be deleted");
        }
        $commission->delete();
        return $this->ok($commission);
    }
}
