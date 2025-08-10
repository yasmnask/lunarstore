<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MasterData\ProductCategoryService;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductCategoryService $service)
    {
        return response()->json(
            $service->getDataTables($request)
        );
    }
}
