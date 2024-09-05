<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;


class Products extends Component
{
    public $products = [];
    public $cart = [];
    public $totalQuantity = 0;
    public $totalAmount = 0;
    public $showCamera = true; // New property to manage camera visibility

    public function mount()
    {
        // Load products from JSON or database
        $productsJson = file_get_contents(public_path('js/products.json'));

        $this->products = $productsJson ? json_decode($productsJson, true) : [];
        // Load cart from session
        if (session()->has('cart')) {
            $this->cart = session('cart');
            $this->updateCartTotals();
        }

        if ($this->showCamera) {
            $this->dispatch('cameraToggledOn');
        }
    }
    public function toggleView()
    {
        $this->showCamera = !$this->showCamera;

        if ($this->showCamera) {
            $this->dispatch('cameraToggledOn');
        }
    }

    public function addToCart($productId)
    {
        // Fixed logical error while adding items to cart.
        $index = $this->findProductInCart($productId);
        
        if ($index !== false) {
            // If the item is already in the cart, just increase the quantity
            $this->cart[$index]['quantity']++;
        } else {
            // If the item is not in the cart, add it with quantity 1
            $this->cart[] = ['product_id' => $productId, 'quantity' => 1];
        }
    
        $this->updateCartTotals();
        $this->saveCartToSession();
    }
    

    public function changeQuantityCart($productId, $type)
    {
        $index = $this->findProductInCart($productId);
        
        if ($index >= 0) {
            if ($type === 'plus') {
                $this->cart[$index]['quantity']++;
            } elseif ($type === 'minus') {
                $this->cart[$index]['quantity']--;
                if ($this->cart[$index]['quantity'] <= 0) {
                    unset($this->cart[$index]);
                    $this->cart = array_values($this->cart); // Reindex the array
                }
            }
        }

        $this->updateCartTotals();
        $this->saveCartToSession();
    }

    public function updateCartQuantity($productId, $newQuantity)
    {
        $index = $this->findProductInCart($productId);

        if ($index >= 0 && $newQuantity > 0) {
            $this->cart[$index]['quantity'] = $newQuantity;
        } elseif ($newQuantity <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // Reindex the array
        }

        $this->updateCartTotals();
        $this->saveCartToSession();
    }

    private function findProductInCart($productId)
    {
        return collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });
    }

    private function updateCartTotals()
    {
        $this->totalQuantity = 0;
        $this->totalAmount = 0;

        foreach ($this->cart as $item) {
            $product = collect($this->products)->firstWhere('id', $item['product_id']);
            $this->totalQuantity += $item['quantity'];
            $this->totalAmount += $product['price'] * $item['quantity'];
        }
    }

    private function saveCartToSession()
    {
        session(['cart' => $this->cart]);
    }

    public function render()
    {
        return view('livewire.products');
    }
}
