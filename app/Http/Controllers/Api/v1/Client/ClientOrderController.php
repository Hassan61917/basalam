<?php

namespace App\Http\Controllers\Api\v1\Client;

use App\Enums\OrderStatus;
use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\OrderResource;
use App\Models\Order;
use App\ModelServices\Financial\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientOrderController extends AuthUserController
{
    protected string $resource = OrderResource::class;

    public function __construct(
        private OrderService $orderService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrdersFor($this->authUser())->withCount("items");
        return $this->ok($this->paginate($orders));
    }

    public function current(): JsonResponse
    {
        $orders = $this->orderService->draftOrders($this->authUser())->withCount("items");;
        return $this->ok($this->paginate($orders));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load("items");
        return $this->ok($order);
    }

    public function setAddress(Order $order, Request $request): JsonResponse
    {
        $data = $request->validate([
            "address" => "required|string",
        ]);
        $order->update(["address" => $data["address"]]);
        return $this->ok($order);
    }

    public function discount(Order $order, Request $request): JsonResponse
    {
        $data = $request->validate([
            "code" => "required|string|exists:discounts,code",
        ]);
        $this->orderService->applyDiscount($order, $data["code"]);
        return $this->message("discount applied successfully");
    }

    public function pay(Order $order): JsonResponse
    {
        $order = $this->orderService->pay($order);
        return $this->ok($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        if ($order->status != OrderStatus::Draft->value) {
            return $this->error(406, "paid orders can not be deleted");
        }
        $order->delete();
        return $this->deleted();
    }
}
