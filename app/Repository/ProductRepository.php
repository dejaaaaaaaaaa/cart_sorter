<?php

namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
    public function getAll()
    {
        return Product::latest()->with('stores')->get();
    }

    public function store($data)
    {
        return Product::create($data);
    }

    public function findById($id)
    {
        return Product::with('stores')->findOrFail($id);
    }

    public function update($id, $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product->refresh();
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        $product->delete();
    }

    public function updateOrCreate($toComapre, $data){
        return Product::updateOrCreate($toComapre, $data);
    }
}
