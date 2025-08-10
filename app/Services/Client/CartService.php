<?php

namespace App\Services\Client;

use App\Models\Cart;
use App\Models\ProductDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart($data)
    {
        $userId = Auth::guard('web')->id();

        if (!$userId) {
            throw new \Exception('User must be authenticated to add items to cart');
        }

        // Validate that the product detail exists
        $productDetail = \App\Models\ProductDetail::find($data['product_detail_id']);
        if (!$productDetail) {
            throw new \Exception('Product not found');
        }

        // Check if item already exists in cart
        $existingItem = Cart::where('user_id', $userId)
            ->where('product_detail_id', $data['product_detail_id'])
            ->first();

        if ($existingItem) {
            // Update quantity if item exists
            $existingItem->increment('quantity', $data['quantity'] ?? 1);
            return $existingItem;
        }

        // Create new cart item
        return Cart::create([
            'user_id' => $userId,
            'product_detail_id' => $data['product_detail_id'],
            'quantity' => $data['quantity'] ?? 1,
        ]);
    }

    public function getCartItems()
    {
        $userId = Auth::guard('web')->id();

        return Cart::with([
            'productDetail.product.category',
            'productDetail.productType'
        ])
            ->where('user_id', $userId)
            ->get()
            ->map(function ($cartItem) {
                $productDetail = $cartItem->productDetail;
                $product = $productDetail->product;
                $category = $product->category;
                $productType = $productDetail->productType;

                return [
                    'id' => $cartItem->id,
                    'product_detail_id' => $cartItem->product_detail_id,
                    'quantity' => $cartItem->quantity,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->app_name,
                        'description' => $product->description,
                        'cover_img' => $product->cover_img,
                        'category' => [
                            'id' => $category->id,
                            'title' => $category->title,
                            'slug' => $category->slug
                        ],
                        'is_topup' => $product->is_topup,
                        'ready_stock' => $product->ready_stock,
                        'notes' => $product->notes
                    ],
                    'plan' => $productType ? [
                        'id' => $productType->id,
                        'name' => $productType->type_name,
                        'description' => $productType->description,
                        'unit' => $productType->unit
                    ] : null,
                    'duration' => $productDetail->duration,
                    'price' => $productDetail->price,
                    'subtotal' => $productDetail->price * $cartItem->quantity,
                    'notes' => $productDetail->notes,
                ];
            });
    }

    public function updateQuantity($cartId, $quantity)
    {
        $userId = Auth::guard('web')->id();

        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Cart item not found');
        }

        if ($quantity <= 0) {
            return $this->removeItem($cartId);
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem;
    }

    public function removeItem($cartId)
    {
        $userId = Auth::guard('web')->id();

        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Cart item not found');
        }

        $cartItem->delete();
        return true;
    }

    public function clearCart()
    {
        $userId = Auth::guard('web')->id();
        return Cart::where('user_id', $userId)->delete();
    }

    public function getCartCount()
    {
        $userId = Auth::guard('web')->id();
        return Cart::where('user_id', $userId)->sum('quantity');
    }

    public function getCartTotal()
    {
        $cartItems = $this->getCartItems();

        return $cartItems->sum('subtotal');
    }

    public function getCartSummary()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum('subtotal');
        $total = $subtotal;

        $cartItems = $cartItems->map(function ($item) use ($total) {
            $item['formatted_price'] = $this->formatPrice($item['price']);
            $item['formatted_subtotal'] = $this->formatPrice($item['subtotal']);
            return $item;
        });

        return [
            'items' => $cartItems,
            'count' => $cartItems->sum('quantity'),
            'subtotal' => $subtotal,
            'formatted_subtotal' => $this->formatPrice($subtotal),
            'formatted_total' => $this->formatPrice($total),
            'total' => $total,
        ];
    }

    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
