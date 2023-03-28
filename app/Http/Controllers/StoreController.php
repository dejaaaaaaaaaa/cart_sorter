<?php

namespace App\Http\Controllers;

use Session;
use App\Http\Requests\StoreCreateRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(Request $request, StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        $store = $this->storeService->index();
        return response()->json(['data' => $store]);
    }

    public function store(StoreCreateRequest $request)
    {
        $data = $request->validated();
        $store = $this->storeService->store($data);
        return response()->json([
            'store' => $store
        ]);
    }

    public function show($id)
    {
        return response()->json([
        'store' => $this->storeService->findById($id)
        ]);
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $this->storeService->update($id, $data);

        return response()->json();
    }

    public function destroy($store)
    {
        $this->storeService->delete($store);

        return response()->json();
    }
}
