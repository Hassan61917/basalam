<?php

use App\Http\Controllers\Api\v1\Client\ClientDiscountController;
use App\Http\Controllers\Api\v1\Client\ClientOrderController;
use App\Http\Controllers\Api\v1\Client\ClientOrderItemController;
use App\Http\Controllers\Api\v1\Client\ClientQuestionController;
use App\Http\Controllers\Api\v1\Client\ClientReviewController;
use App\Http\Controllers\Api\v1\User\UseOrderController;
use App\Http\Controllers\Api\v1\User\UserBlockController;
use App\Http\Controllers\Api\v1\User\UserDiscountController;
use App\Http\Controllers\Api\v1\User\UserMessageController;
use App\Http\Controllers\Api\v1\User\UserPageController;
use App\Http\Controllers\Api\v1\User\UserProductController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use App\Http\Controllers\Api\v1\User\UserQuestionController;
use App\Http\Controllers\Api\v1\User\UserReviewController;
use App\Http\Controllers\Api\v1\User\UserShopController;
use App\Http\Controllers\Api\v1\User\UserWalletController;
use App\Http\Controllers\Api\v1\User\UserWalletTransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource("/profile", UserProfileController::class)->except(["show", "delete"]);

Route::prefix("wallet")->name("wallet.")->group(function () {
    Route::get("/", [UserWalletController::class, "index"])->name("index");
    Route::post("update-password", [UserWalletController::class, "setPassword"])->name("update-password");
//    Route::post("/deposit", [UserWalletController::class, "deposit"])->name("deposit");
//    Route::post("/withdraw", [UserWalletController::class, "withdraw"])->name("withdraw");
});
Route::apiResource("wallet-transactions", UserWalletTransactionController::class)->except("store", "destroy");

Route::prefix("shop")->name("shop.")->group(function () {
    Route::get("/", [UserShopController::class, "index"])->name("index");
    Route::post("/", [UserShopController::class, "store"])->name("store");
    Route::put("/", [UserShopController::class, "update"])->name("update");
    Route::delete("/", [UserShopController::class, "destroy"])->name("destroy");
    Route::apiResource("/discounts", UserDiscountController::class);
    Route::prefix("orders")->name("orders.")->group(function () {
        Route::get('/', [UseOrderController::class, "index"])->name("index");
        Route::get("orders/{order-item}",[UseOrderController::class, "show"])->name("show");
        Route::get("orders/{order-item}/cancel",[UseOrderController::class, "cancel"])->name("cancel");
        Route::get("orders/{order-item}/accept",[UseOrderController::class, "accept"])->name("accept");
        Route::get("orders/{order-item}/ship",[UseOrderController::class, "ship"])->name("ship");
    });
    Route::get("/reviews", [UserReviewController::class, 'index'])->name('reviews.index');
    Route::get("/reviews/{review}", [UserReviewController::class, 'show'])->name('reviews.show');
    Route::get("/reviews/{review}/reply", [UserReviewController::class, 'reply'])->name('reviews.reply');

    Route::get("/questions", [UserQuestionController::class, "index"])->name("questions.index");
    Route::get("/questions/{question}", [UserQuestionController::class, "show"])->name("questions.show");
    Route::post("/questions/{question}/answer", [UserQuestionController::class, "answer"])->name("questions.answer");

});

Route::apiResource("/products", UserProductController::class);
Route::post("/products/{product}/available", [UserProductController::class, "available"])->name("products.available");
Route::post("/products/{product}/unavailable", [UserProductController::class, "unavailable"])->name("products.unavailable");
Route::post("/products/{product}/add-option", [UserProductController::class, "addOption"])->name("products.add-option");
Route::post("/products/{product}/remove-option", [UserProductController::class, "removeOption"])->name("products.remove-option");

Route::get("used-discounts", [ClientDiscountController::class, "used"])->name("discounts.used");
Route::get("discounts", [ClientDiscountController::class, "index"])->name("discounts.index");
Route::get("discounts/{discount}", [ClientDiscountController::class, "show"])->name("discounts.show");

Route::get("orders/current", [ClientOrderController::class, "current"])->name("orders.current");
Route::apiResource("orders", ClientOrderController::class);
Route::post("/orders/{order}/discount", [ClientOrderController::class, 'discount'])->name('order.discount');
Route::post("/orders/{order}/pay", [ClientOrderController::class, 'pay'])->name('order.pay');
Route::post("/orders/{order}/cancel", [ClientOrderController::class, 'cancel'])->name('order.cancel');
Route::post("/orders/{order}/set-address", [ClientOrderController::class, 'setAddress'])->name('order.setAddress');

Route::apiResource("order-items", ClientOrderItemController::class);
Route::post("/order-items/{order_item}/next", [ClientOrderItemController::class, 'next'])->name('order-items.next');
Route::post("/order-items/{order_item}/cancel", [ClientOrderItemController::class, 'cancel'])->name('order-items.cancel');
Route::post("/order-items/{order_item}/complete", [ClientOrderItemController::class, 'complete'])->name('order-items.complete');

Route::apiResource("reviews", ClientReviewController::class);

Route::apiResource("questions", ClientQuestionController::class);

Route::apiResource("page", UserPageController::class)->only("index", "update");

Route::apiResource("blocks", UserBlockController::class);
Route::apiResource("messages", UserMessageController::class)->except("index");
Route::get("inbox", [UserMessageController::class, "inbox"])->name("inbox");
Route::get("outbox", [UserMessageController::class, "outbox"])->name("outbox");
Route::get("chats", [UserMessageController::class, "chats"])->name("chats");
Route::get("/{user}/chat", [UserMessageController::class, "chat"])->name("chat");
