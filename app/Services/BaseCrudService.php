<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseCrudService
{
    protected string $modelClass;

    public function list(): Collection
    {
        return $this->modelClass::query()->latest()->get();
    }

    public function findOrFail(int $id): Model
    {
        return $this->modelClass::query()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->modelClass::query()->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);

        return $model->refresh();
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id)->delete();
    }
}
