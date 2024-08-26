<?php

namespace App\Livewire;

use Livewire\Component;
use app\Models\Cart;
use app\Models\User;
use app\Models\Groceries;
use app\Models\FaceData;

class Shop extends Component
{
    public function render()
    {
        return view('livewire.shop');
    }

    
}
