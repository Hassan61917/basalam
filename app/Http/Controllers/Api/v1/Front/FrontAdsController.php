<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\AdsOrderResource;
use App\ModelServices\Ads\AdsService;
use Illuminate\Http\JsonResponse;

class FrontAdsController extends Controller
{
    protected string $resource = AdsOrderResource::class;

    public function __construct(
        private AdsService $adsService
    )
    {
    }

    public function index(): JsonResponse
    {
        $orders = $this->adsService->getAvailableOrders();
        return $this->ok($this->paginate($orders));
    }
}
