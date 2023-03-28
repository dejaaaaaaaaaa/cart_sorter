<?php

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return $this->productRepository->getAll();
    }

    public function findById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function store($data)
    {
        return Product::create($data);
        return $this->productRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete($id)
        {
            $this->productRepository->delete($id);
        }
}
