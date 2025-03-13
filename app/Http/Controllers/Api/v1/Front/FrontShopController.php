<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ShopResource;
use App\Models\Shop;
use App\ModelServices\Ads\LadderService;
use Illuminate\Http\JsonResponse;

class FrontShopController extends Controller
{
    protected string $resource = ShopResource::class;

    public function __construct(
        private LadderService $ladderService
    )
    {
    }

    public function index(): JsonResponse
    {
        $services = $this->ladderService->getAvailableShop()->with("user");
        return $this->ok($this->paginate($services));
    }

    public function show(Shop $shop): JsonResponse
    {
        $shop->load("user", "products");
        return $this->ok($shop);
    }
}
