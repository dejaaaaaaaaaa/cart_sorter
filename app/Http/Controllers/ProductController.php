<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Session;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(Request $request, ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $product = $this->productService->index();
        return response()->json(['data' => $product]);
    }

    public function store(ProductCreateRequest $request)
    {
        $data = $request->validated();
        $product = $this->productService->store($data);
        return response()->json([
            'product' => $product
        ]);
    }

    public function show($id)
    {
        return response()->json([
        'product' => $this->productService->findById($id)
        ]);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $this->productService->update($id, $data);

        return response()->json();
    }

    public function destroy($product)
    {
        $this->productService->delete($product);
        return response()->json();
    }

    public function attachStores(Product $product, Request $request)
    {
        $product->stores()->sync($request->stores);
        return response()->json();
    }
}
