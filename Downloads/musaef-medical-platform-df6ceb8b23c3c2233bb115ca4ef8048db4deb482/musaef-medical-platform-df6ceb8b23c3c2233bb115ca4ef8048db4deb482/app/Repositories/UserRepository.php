<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function getUsersByRole(string $role): Collection
    {
        return User::where('role', $role)->get();
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        $user = $this->getUserById($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}