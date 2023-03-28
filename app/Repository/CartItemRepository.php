<?php

namespace App\Repository;

use App\Models\CartItem;

class CartItemRepository
{
    public function getAll()
    {
        return CartItem::latest()->get();
    }

    public function store($data)
    {
        return CartItem::create($data);
    }

    public function findById($id)
    {
        return CartItem::findOrFail($id);
    }

    public function update($id, $data)
    {
        $cartItem = $this->findById($id);
        $cartItem->update($data);
        return $cartItem->refresh();
    }

    public function delete($id)
        {
            $cartItem = $this->findById($id);
            $cartItem->delete();
        }
}
