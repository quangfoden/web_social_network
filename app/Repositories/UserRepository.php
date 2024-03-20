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
    public function getUserById(int $id): User
    {
        return $this->model->query()->find($id);
    }

    public function createUser(array $userData)
    {
        return User::create($userData);
    }
    public function update(int $id, array $data): bool|int
    {
        return $this->model->query()->find($id)->update($data);
    }
}
