<?php

namespace App\Services\Admin\MasterData;

use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAdminService
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
            3 => 'full_name',
            4 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = UserAdmin::select('id', 'username', 'full_name', 'created_at');

        $recordsTotal = UserAdmin::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $admins = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $formattedData = $admins->map(function ($admin) {
            return [
                'id' => $admin->id,
                'username' => $admin->username,
                'full_name' => $admin->full_name,
                'created_at' => $admin->created_at->format('d F Y H:i'),
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
     * Create a new admin.
     *
     * @param array $data
     * @return UserAdmin
     * @throws ValidationException
     */
    public function createAdmin(array $data): UserAdmin
    {
        $adminData = [
            'username' => $data['username'],
            'full_name' => $data['full_name'],
            'password' => Hash::make($data['password']),
            'created_at' => now(),
        ];

        return UserAdmin::create($adminData);
    }

    /**
     * Update an existing admin.
     *
     * @param int $adminId
     * @param array $data
     * @return UserAdmin
     * @throws ValidationException
     */
    public function updateAdmin(int $adminId, array $data): UserAdmin
    {
        $admin = UserAdmin::findOrFail($adminId);

        $adminData = [
            'username' => $data['username'],
            'full_name' => $data['full_name'],
        ];

        if (!empty($data['password'])) {
            $adminData['password'] = Hash::make($data['password']);
        }

        $admin->update($adminData);

        if (Auth::guard('admin')->id() == $adminId) {
            session()->put('admin', Auth::guard('admin')->user());
        }

        return $admin;
    }
}
