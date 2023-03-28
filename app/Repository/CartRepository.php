<?php

namespace App\Repository;

use App\Models\Cart;

class CartRepository
{
    public function getAll()
    {
        return Cart::latest()->get();
    }

    public function store($data)
    {
        return Cart::create($data);
    }

    public function findById($id)
    {
        return Cart::findOrFail($id);
    }

    public function update($id, $data)
    {
        $cart = $this->findById($id);
        $cart->update($data);
        return $cart->refresh();
    }

    public function delete($id)
        {
            $cart = $this->findById($id);
            $cart->delete();
        }
}
