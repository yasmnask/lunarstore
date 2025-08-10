<?php

namespace App\Services\Admin\MasterData;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function getDataTables(Request $request): array
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc');

        $columns = [
            1 => 'id',
            2 => 'username',
            3 => 'name',
            4 => 'email',
            5 => 'phone',
            6 => 'address',
            7 => 'is_active',
            8 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = User::select('id', 'username', 'name', 'email', 'phone', 'address', 'is_active', 'created_at');

        $recordsTotal = User::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $users = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $formattedData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'address' => $user->address ?? '-',
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->format('d F Y H:i'),
            ];
        });

        return [
            'draw' => (int) $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $formattedData,
        ];
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function createUser(array $data): User
    {
        $userData = [
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => true,
            'created_at' => now(),
        ];

        return User::create($userData);
    }

    /**
     * Update an existing user.
     *
     * @param int $userId
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function updateUser(int $userId, array $data): User
    {
        $user = User::findOrFail($userId);

        $userData = [
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        $user->update($userData);

        return $user;
    }

    /**
     * Update a specific field of a user.
     *
     * @param int $userId
     * @param string $field
     * @param mixed $value
     * @return User
     */
    public function updateUserField(int $userId, string $field, $value): User
    {
        $user = User::findOrFail($userId);

        $user->update([$field => $value]);

        return $user;
    }

    /**
     * Toggle user active status (soft delete).
     *
     * @param int $userId
     * @param bool $status
     * @return User
     */
    public function toggleUserStatus(int $userId, bool $status): User
    {
        $user = User::findOrFail($userId);

        $user->update(['is_active' => $status]);

        return $user;
    }
}
