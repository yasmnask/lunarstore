<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Services\Admin\MasterData\ProductDetailService;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductDetailService $service)
    {
        return response()->json(
            $service->getDataTables($request)
        );
    }
}
