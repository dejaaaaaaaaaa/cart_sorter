<?php

namespace App\Http\Controllers;

use Session;
use App\Http\Requests\CartItemCreateRequest;
use App\Http\Requests\CartItemUpdateRequest;
use App\Models\CartItem;
use App\Services\CartItemService;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected $cartItemService;

    public function __construct(Request $request, CartItemService $cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    public function index(Request $request)
    {
        $cartItem = $this->cartItemService->index();
        return response()->json(['data' => $cartItem]);
    }

    public function store(CartItemCreateRequest $request)
    {
        $data = $request->validated();
        $cartItem = $this->cartItemService->store($data);
        return response()->json([
            'cartItem' => $cartItem
        ]);
    }

    public function show($id)
    {
        return response()->json([
        'cartItem' => $this->cartItemService->findById($id)
        ]);
    }

    public function update(CartItemUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $this->cartItemService->update($id, $data);

        return response()->json();
    }

    public function destroy($cartitem)
    {
        $this->cartItemService->delete($cartitem);

        return response()->json();
    }
}
