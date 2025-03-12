<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\OrderItemResource;
use App\Models\OrderItem;
use App\ModelServices\Financial\OrderItemService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UseOrderController extends ShopController
{
    protected string $resource = OrderItemResource::class;
    protected ?string $ownerRelation = "shop";

    public function __construct(
        public OrderItemService $itemService
    )
    {
    }
    public function index(): JsonResponse
    {
        $orders = $this->itemService->getItemsFor($this->authUser()->shop);
        return $this->ok($this->paginate($orders));
    }

    public function show(OrderItem $item): JsonResponse
    {
        $item->load(["user"]);
        return $this->ok($item);
    }

    public function cancel(OrderItem $item): JsonResponse
    {
        $this->itemService->cancel($item);
        return $this->ok($item);
    }

    public function accept(OrderItem $item): JsonResponse
    {
        $this->itemService->confirm($item);
        return $this->ok($item);
    }

    public function ship(OrderItem $item): JsonResponse
    {
        $this->itemService->ship($item);
        return $this->ok($item);
    }
}
