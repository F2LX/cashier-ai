<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Groceries;
use Illuminate\Support\Facades\Storage;

class Products extends Component
{
    public $products = [];
    public $cart = [];
    public $totalQuantity = 0;
    public $totalAmount = 0;
    public $showCamera = true;

    public function mount()
{
    $this->products = Groceries::all()->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->product_name,
            'class' => $product->class,
            'price' => $product->price,
            'image' => Storage::url($product->thumbnail) // Generate URL for thumbnail
        ];
    })->keyBy('id')->toArray(); // Key products by their id

    if (session()->has('cart')) {
        // Just assign the cart directly from the session without re-keying it
        $this->cart = session('cart');
    
        // Update totals after loading the cart
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
    if (isset($this->cart[$productId])) {
        $this->cart[$productId]['quantity']++;
    } else {
        $this->cart[$productId] = ['product_id' => $productId, 'quantity' => 1];
    }
    
    $this->updateCartTotals();
    $this->saveCartToSession();
}

    

    public function changeQuantityCart($productId, $type)
    {
        $index = $this->findProductInCart($productId);

        if ($index !== false) {
            if ($type === 'plus') {
                $this->cart[$index]['quantity']++;
            } elseif ($type === 'minus') {
                $this->cart[$index]['quantity']--;
                if ($this->cart[$index]['quantity'] <= 0) {
                    unset($this->cart[$index]);
                    $this->cart = array_values($this->cart); // Reindex the array
                }
            }

            $this->updateCartTotals();
            $this->saveCartToSession();
        }
    }

    public function updateCartQuantity($productId, $newQuantity)
{
    if ($newQuantity > 0) {
        $this->cart[$productId]['quantity'] = $newQuantity;
    } else {
        unset($this->cart[$productId]);
    }

    $this->updateCartTotals();
    $this->saveCartToSession();
}

    

private function findProductInCart($productId)
{
    // Directly check if the productId exists as a key in the cart array
    return array_key_exists($productId, $this->cart) ? $productId : false;
}

    private function updateCartTotals()
{
    $this->totalQuantity = 0;
    $this->totalAmount = 0;

    foreach ($this->cart as $productId => $item) {
        $product = collect($this->products)->firstWhere('id', $productId); // Use $productId directly
        if ($product) {
            $this->totalQuantity += $item['quantity'];
            $this->totalAmount += $product['price'] * $item['quantity'];
        }
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
