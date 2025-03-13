<?php

namespace App\ModelServices\Ads;

use App\Enums\ShowStatus;
use App\Exceptions\ModelException;
use App\Models\ShowModel;
use Carbon\Carbon;

trait OrderShowable
{
    public function updateShowingTime(ShowModel $order): void
    {
        $value = $this->getShowingTime($order);
        if (!$this->isWaiting($order)) {
            return;
        }
        $order->show_at = $value;
        $order->save();
    }

    private function getShowingTime(ShowModel $ads): Carbon
    {
        $lastOrder = $ads->lastOrder();
        $result = now();
        if ($lastOrder) {
            $result = Carbon::make($lastOrder->end_at);
        }
        return $result->addMinute();
    }

    private function complete(ShowModel $order): void
    {
        $this->updateOrderStatus($order, ShowStatus::Completed);
    }

    private function show(ShowModel $order, int $duration): void
    {
        $status = $order->status;
        if ($status != ShowStatus::Waiting->value || !$order->order->isPaid()) {
            throw new ModelException("order can not be showed");
        }
        $order->update([
            "end_at" => Carbon::make($order->show_at)->addDays($duration)
        ]);
        $this->updateOrderStatus($order, ShowStatus::Showing);
    }

    private function cancel(ShowModel $order): void
    {
        $amount = $this->getCancelAmount($order);
        $this->orderService->cancel($order->orderItem, $amount);
        $this->updateOrderStatus($order, ShowStatus::Waiting);
    }

    private function getCancelAmount(ShowModel $order)
    {
        $status = $order->status;
        $amount = $order->orderItem->total_price - $order->orderItem->discount_price;
        if ($status == ShowStatus::Showing->value) {
            $price = $amount / $order->ads->duration;
            $result = +Carbon::make($order->end_at)->diff(now())->format('%d') + 1;
            $amount = $price * $result;
        }
        return $amount;
    }

    private function updateOrderStatus(ShowModel $order, ShowStatus $status): void
    {
        $order->update(["status" => $status->value]);
    }

    private function isWaiting(ShowModel $order): bool
    {
        return $order->status == ShowStatus::Waiting->value;
    }
}
