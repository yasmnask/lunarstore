<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Services\Admin\MasterData\ProductTypeService;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductTypeService $service)
    {
        return response()->json(
            $service->getDataTables($request)
        );
    }
}
