<?php

namespace App\Services;

use App\Models\Store;
use App\Repository\StoreRepository;

class StoreService
{
    protected $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        return $this->storeRepository->getAll();
    }

    public function findById($id)
    {
        return $this->storeRepository->findById($id);
    }

    public function store($data)
    {
        return Store::create($data);
        return $this->storeRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->storeRepository->update($id, $data);
    }

    public function delete($id)
        {
            $this->storeRepository->delete($id);
        }
}
