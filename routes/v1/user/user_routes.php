<?php

use App\Http\Controllers\Api\v1\User\UserProductController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
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
});

Route::apiResource("/products", UserProductController::class);
Route::post("/products/{product}/available", [UserProductController::class, "available"])->name("products.available");
Route::post("/products/{product}/unavailable", [UserProductController::class, "unavailable"])->name("products.unavailable");
Route::post("/products/{product}/add-option", [UserProductController::class, "addOption"])->name("products.add-option");
Route::post("/products/{product}/remove-option", [UserProductController::class, "removeOption"])->name("products.remove-option");
