<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Transaction;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

#[Title('Admin Dashboard')]
class Dashboard extends Component
{
    public $totalUsers;
    public $totalProducts;
    public $totalCategories;
    public $totalRevenue;
    public $salesTrend = [];
    public $productTypes = [];
    public $bestProducts = [];
    public $popularCategories = [];
    public $latestProducts = [];
    public $recentTransactions = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Basic statistics
        $this->totalUsers = User::count();
        $this->totalProducts = Product::count();
        $this->totalCategories = ProductCategory::count();
        $this->totalRevenue = Transaction::where('payment_status', 'paid')->sum('final_amount');

        // Sales trend for last 7 days
        $this->salesTrend = $this->getSalesTrend();

        // Product types distribution
        $this->productTypes = $this->getProductTypesData();

        // Best selling products
        $this->bestProducts = $this->getBestProducts();

        // Popular categories
        $this->popularCategories = $this->getPopularCategories();

        // Latest products
        $this->latestProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Recent transactions
        $this->recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();
    }

    private function getSalesTrend()
    {
        $days = collect();
        $data = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days->push($date->format('M j'));

            $dayRevenue = Transaction::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('final_amount');

            $data->push($dayRevenue);
        }

        return [
            'labels' => $days->toArray(),
            'data' => $data->toArray()
        ];
    }

    private function getProductTypesData()
    {
        return DB::table('product_details')
            ->join('product_types', 'product_details.product_type_id', '=', 'product_types.id')
            ->select('product_types.type_name', DB::raw('COUNT(*) as count'))
            ->groupBy('product_types.type_name')
            ->get()
            ->toArray();
    }

    private function getBestProducts()
    {
        return DB::table('transaction_details')
            ->join('product_details', 'transaction_details.product_detail_id', '=', 'product_details.id')
            ->join('products', 'product_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.payment_status', 'paid')
            ->select('products.app_name', DB::raw('SUM(transaction_details.quantity) as total_sold'))
            ->groupBy('products.id', 'products.app_name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get()
            ->toArray();
    }

    private function getPopularCategories()
    {
        $categories = ProductCategory::withCount('products')->get();
        $maxCount = $categories->max('products_count');

        return $categories->map(function ($category) use ($maxCount) {
            return [
                'title' => $category->title,
                'product_count' => $category->products_count,
                'percentage' => $maxCount > 0 ? ($category->products_count / $maxCount) * 100 : 0,
                'image' => $category->image
            ];
        })->sortByDesc('product_count')->take(5)->values()->toArray();
    }

    public function getChartData($type = 'sales')
    {
        if ($type === 'users') {
            $days = collect();
            $data = collect();

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $days->push($date->format('M j'));

                $dayUsers = User::whereDate('created_at', $date)->count();
                $data->push($dayUsers);
            }

            return response()->json([
                'labels' => $days->toArray(),
                'data' => $data->toArray()
            ]);
        }

        return response()->json($this->salesTrend);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}
