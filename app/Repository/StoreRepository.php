<?php

namespace App\Repository;

use App\Models\Store;

class StoreRepository
{
    public function getAll()
    {
        return Store::latest()->get();
    }

    public function store($data)
    {
        return Store::create($data);
    }

    public function findById($id)
    {
        return Store::findOrFail($id);
    }

    public function update($id, $data)
    {
        $store = $this->findById($id);
        $store->update($data);
        return $store->refresh();
    }

    public function delete($id)
        {
            $store = $this->findById($id);
            $store->delete();
        }
}
