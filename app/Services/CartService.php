<?php

namespace App\Services;

use App\Models\Cart;
use App\Repository\CartRepository;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        return $this->cartRepository->getAll();
    }

    public function findById($id)
    {
        return $this->cartRepository->findById($id);
    }

    public function store($data)
    {
        return Cart::create($data);
        return $this->cartRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->cartRepository->update($id, $data);
    }

    public function delete($id)
        {
            $this->cartRepository->delete($id);
        }
}
