<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * جلب كافة المستخدمين مع دعم التقسيم الافتراضي لرفع الأداء
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::latest()->paginate($perPage);
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * جلب المستخدمين حسب الرتبة مع دعم التقسيم المباشر
     */
    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator
    {
        return User::where('role', $role)->latest()->paginate($perPage);
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
