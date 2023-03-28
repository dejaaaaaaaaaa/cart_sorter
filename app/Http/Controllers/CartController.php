<?php

namespace App\Http\Controllers;

use Session;
use App\Http\Requests\CartCreateRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(Request $request, CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->index();
        return response()->json(['data' => $cart]);
    }

    public function store(CartCreateRequest $request)
    {
        $data = $request->validated();
        $cart = $this->cartService->store($data);
        return response()->json([
            'cart' => $cart
        ]);
    }

    public function show($id)
    {
        return response()->json([
        'cart' => $this->cartService->findById($id)
        ]);
    }

    public function update(CartUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $this->cartService->update($id, $data);

        return response()->json();
    }

    public function destroy($cart)
    {
        $this->cartService->delete($cart);

        return response()->json();
    }
}
