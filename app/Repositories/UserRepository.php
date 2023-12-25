<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getUserByEmail(string $email): mixed
    {
        return $this->model->query()->where('email', $email)->first();
    }

    public function update(int $id, array $data): bool|int
    {
        return $this->model->query()->find($id)->update($data);
    }
}
