<?php

namespace App\Http\Controllers\Api\v1\Client;

use App\Exceptions\ModelException;
use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserOrderRequest;
use App\Http\Resources\v1\OrderItemResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\ModelServices\Financial\OrderItemService;
use Illuminate\Http\JsonResponse;

class ClientOrderItemController extends AuthUserController
{
    protected string $resource = OrderItemResource::class;

    public function __construct(
        private OrderItemService $itemService
    )
    {
    }

    public function index(): JsonResponse
    {
        $order = $this->itemService->getOrder($this->authUser());
        $items = $order->items();
        return $this->ok($this->paginate($items));
    }

    public function store(UserOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = Product::find($data['product_id']);
        if (!$product->isAvailable()) {
            throw new ModelException("Product is Not Available");
        }
        $item = $this->itemService->order($this->authUser(), $product, $data["quantity"] ?? 1);
        return $this->ok($item);
    }

    public function show(OrderItem $item): JsonResponse
    {
        return $this->ok($item);
    }

    public function cancel(OrderItem $item): JsonResponse
    {
        $this->itemService->cancel($item);
        return $this->ok($item);
    }

    public function complete(OrderItem $item): JsonResponse
    {
        $this->itemService->complete($this->authUser(), $item);
        return $this->ok($item);
    }

    public function next(OrderItem $item): JsonResponse
    {
        $this->itemService->moveToNext($this->authUser(), $item);
        return $this->ok($item);
    }

    public function destroy(OrderItem $item): JsonResponse
    {
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }
        return $this->deleted();
    }
}
