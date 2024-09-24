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
    protected $listeners = ['objdetection'];

    public function mount()
    {
        // Use Eloquent's pluck to optimize retrieval of key data
        $this->products = Groceries::all()->mapWithKeys(function ($product) {
            return [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'class' => $product->class,
                    'price' => $product->price,
                    'image' => Storage::url($product->thumbnail),
                ]
            ];
        })->toArray(); // No need to re-key by 'id' as we directly use it in mapWithKeys

        // Load cart from session
        if (session()->has('cart')) {
            $this->cart = session('cart');
            $this->updateCartTotals();
        }

        // Only dispatch the camera event if showCamera is true
        if ($this->showCamera) {
            $this->dispatch('cameraToggledOn');
        }
    }

    public function toggleView()
    {
        $this->showCamera = !$this->showCamera;

        // Dispatch camera toggled event when needed
        if ($this->showCamera) {
            $this->dispatch('cameraToggledOn');
        }
    }

    public function addToCart($productId)
    {
        // Update cart directly by productId
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = ['product_id' => $productId, 'quantity' => 1];
        }

        // Update totals and session
        $this->updateCartTotals();
        $this->saveCartToSession();
    }

    public function changeQuantityCart($productId, $type)
    {
        // Use array_key_exists for faster cart access
        if (array_key_exists($productId, $this->cart)) {
            if ($type === 'plus') {
                $this->cart[$productId]['quantity']++;
            } elseif ($type === 'minus') {
                $this->cart[$productId]['quantity']--;
                if ($this->cart[$productId]['quantity'] <= 0) {
                    unset($this->cart[$productId]);
                }
            }

            $this->updateCartTotals();
            $this->saveCartToSession();
        }
    }

    public function updateCartQuantity($productId, $newQuantity)
    {
        // Avoid recalculating when no change
        if ($newQuantity > 0 && isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] = $newQuantity;
        } else {
            unset($this->cart[$productId]);
        }

        $this->updateCartTotals();
        $this->saveCartToSession();
    }

    private function updateCartTotals()
    {
        // Initialize totals
        $this->totalQuantity = 0;
        $this->totalAmount = 0;

        // Avoid creating a new collection for every product lookup
        foreach ($this->cart as $productId => $item) {
            if (isset($this->products[$productId])) {
                $this->totalQuantity += $item['quantity'];
                $this->totalAmount += $this->products[$productId]['price'] * $item['quantity'];
            }
        }
    }

    public function objdetection($objclass)
    {
        // Optimize the product search by leveraging array filtering
        $product = collect($this->products)->firstWhere('class', $objclass);

        if ($product) {
            $this->addToCart($product['id']);
        }
    }

    private function saveCartToSession()
    {
        // Store the cart in session
        session(['cart' => $this->cart]);
    }

    public function render()
    {
        return view('livewire.products');
    }
}
