<?php

namespace App\Livewire\Client;

use App\Services\Client\ProductService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Product Catalog')]
class Catalog extends Component
{
    use WithPagination;

    #[Url(as: 'category', keep: true)]
    public $selectedCategory = '';

    public $categories = [];
    public $categoryTitle = 'All Products';
    public $isLoading = false;

    protected $productService;

    // Set pagination view
    protected $paginationTheme = 'bootstrap';

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function mount()
    {
        $this->loadCategories();
        $this->updateCategoryTitle();
    }

    public function loadCategories()
    {
        $this->categories = $this->productService->getCategories();
    }

    public function updatedSelectedCategory()
    {
        $this->updateCategoryTitle();
        $this->resetPage(); // This should reset to page 1
    }

    public function updateCategoryTitle()
    {
        $this->categoryTitle = $this->productService->getCategoryTitle($this->selectedCategory);
    }

    public function filterByCategory($categorySlug)
    {
        $this->isLoading = true;
        $this->selectedCategory = $categorySlug;
        $this->updateCategoryTitle();
        $this->resetPage(); // Reset pagination when filtering
        $this->isLoading = false;
    }

    public function formatPrice($price)
    {
        return $this->productService->formatPrice($price);
    }

    public function truncateDescription($description)
    {
        return $this->productService->truncateDescription($description);
    }

    // Override the pagination view
    public function paginationView()
    {
        return 'livewire.client.catalog-pagination';
    }

    public function render()
    {
        $products = $this->productService->getPaginatedProducts($this->selectedCategory, 8);

        return view('livewire.client.catalog', [
            'products' => $products
        ]);
    }
}
