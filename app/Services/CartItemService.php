<?php

namespace App\Services;

use App\Models\CartItem;
use App\Repository\CartItemRepository;

class CartItemService
{
    protected $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function index()
    {
        return $this->cartItemRepository->getAll();
    }

    public function findById($id)
    {
        return $this->cartItemRepository->findById($id);
    }

    public function store($data)
    {
        return CartItem::create($data);
        return $this->cartItemRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->cartItemRepository->update($id, $data);
    }

    public function delete($id)
        {
            $this->cartItemRepository->delete($id);
        }
}
