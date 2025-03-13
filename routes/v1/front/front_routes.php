<?php

use App\Http\Controllers\Api\v1\Front\FrontAdsController;
use App\Http\Controllers\Api\v1\Front\FrontCategoryController;
use App\Http\Controllers\Api\v1\Front\FrontPageController;
use App\Http\Controllers\Api\v1\Front\FrontPostController;
use App\Http\Controllers\Api\v1\Front\FrontProductController;
use App\Http\Controllers\Api\v1\Front\FrontShopController;
use Illuminate\Support\Facades\Route;

Route::get("advertises", [FrontAdsController::class, "index"])->name("ads.index");

Route::get("categories", [FrontCategoryController::class, "index"])->name("categories.index");
Route::get("categories/{category}", [FrontCategoryController::class, "show"])->name("categories.show");

Route::get("shops", [FrontShopController::class, "index"])->name("services.index");
Route::get("/shops/{shop}",[FrontShopController::class, "show"])->name("services.show");

Route::get("/products", [FrontProductController::class, "index"])->name("service-items.index");
Route::get("/products/{product}",[FrontProductController::class, "show"])->name("service-items.show");


Route::middleware(["auth:sanctum"])->group(function () {
    Route::prefix("social")->name("social.")->group(function () {
        Route::get("/", [FrontPageController::class, "index"])->name("pages.index");
        Route::get("page-posts", [FrontPageController::class, "pagePosts"])->name("pages.posts");
        Route::get("/{page}", [FrontPageController::class, "show"])->name("pages.show");
        Route::post("/{page}/follow", [FrontPageController::class, "follow"])->name("pages.follow");
        Route::post("/{page}/unfollow", [FrontPageController::class, "unFollow"])->name("pages.unfollow");
        Route::get("posts", [FrontPostController::class, "posts"])->name("posts.index");
        Route::get("/posts/{post}", [FrontPostController::class, "show"])->name("posts.show");
    });
});
