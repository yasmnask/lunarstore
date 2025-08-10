<?php

namespace App\Livewire\Client;

use App\Models\ProductCategory;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Title('Premium Digital Products')]
class Home extends Component
{
    public $categories = [];
    public $featuredProducts = [];

    public function mount()
    {
        $this->loadCategories();
        $this->loadFeaturedProducts();
    }

    public function loadCategories()
    {
        $this->categories = ProductCategory::where('is_deleted', false)
            ->orderBy('id', 'DESC')
            ->limit(4)
            ->get(['id', 'title', 'slug', 'description', 'image']);
    }

    public function loadFeaturedProducts()
    {
        $this->featuredProducts = Product::select([
            'products.*',
            DB::raw('(SELECT MIN(price) FROM product_details WHERE product_id = products.id AND is_deleted = false) AS starting_price')
        ])
            ->where('ready_stock', true)
            ->where('is_deleted', false)
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }

    public function formatPrice($price)
    {
        if ($price === null) {
            return '-';
        }
        return 'Rp' . number_format($price, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.client.home');
    }
}
