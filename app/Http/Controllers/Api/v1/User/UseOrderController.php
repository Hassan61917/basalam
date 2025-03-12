<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\OrderItemResource;
use App\Models\OrderItem;
use App\ModelServices\Financial\OrderItemService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UseOrderController extends AuthUserController
{
    protected string $resource = OrderItemResource::class;
    protected ?string $ownerRelation = "shop";

    public function __construct(
        public OrderItemService $itemService
    )
    {
    }

    public function before(?Model $model): void
    {
        if (!$this->authUser()->shop) {
            throw new AuthorizationException("you must be a shop owner");
        }
        parent::before($model);

    }

    public function index(): JsonResponse
    {
        $shop = $this->authUser()->shop;
        $bookings = $this->itemService->getItemsFor($shop);
        return $this->ok($this->paginate($bookings));
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
        $booking = $this->itemService->confirm($item);
        return $this->ok($booking);
    }

    public function ship(OrderItem $item): JsonResponse
    {
        $booking = $this->itemService->ship($item);
        return $this->ok($booking);
    }
}
