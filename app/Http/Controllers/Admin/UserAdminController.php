<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAdmin;
use App\Services\Admin\MasterData\UserAdminService;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserAdminService $service)
    {
        return response()->json(
            $service->getDataTables($request)
        );
    }
}
