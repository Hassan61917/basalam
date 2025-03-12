<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\WishlistResource;
use App\Models\Product;
use App\Models\Wishlist;
use App\ModelServices\Financial\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserWishlistController extends AuthUserController
{
    protected string $resource = WishlistResource::class;

    public function __construct(
        public WishlistService $wishlistService
    )
    {
    }

    public function index(): JsonResponse
    {
        $wishList = $this->wishlistService->getAllFor($this->authUser(), ["product"]);
        return $this->ok($this->paginate($wishList));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
           "product_id" => "required|exists:products,id",
        ]);
        $item = Product::find($data['product_id']);
        $wish = $this->wishlistService->make($this->authUser(), $item);
        return $this->ok($wish);
    }

    public function show(Wishlist $wish): JsonResponse
    {
        $wish->load("item");
        return $this->ok($wish);
    }

    public function destroy(Wishlist $wish): JsonResponse
    {
        $wish->delete();
        return $this->deleted();
    }
}
