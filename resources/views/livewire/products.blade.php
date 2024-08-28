<div>
    <div class="container">
        <header>
            <div class="title">PRODUCT LIST</div>
            <div class="icon-cart">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1"/>
                </svg>
                <span>{{ $totalQuantity }}</span>
            </div>
        </header>
        <div class="listProduct">
            @foreach ($products as $product)
            <div class="item" data-id="{{ $product['id'] }}">
                <img src="{{ $product['image'] }}" alt="">
                <h2>{{ $product['name'] }}</h2>
                <div class="price">${{ $product['price'] }}</div>
                <button wire:click="addToCart({{ $product['id'] }})" class="addCart">Add To Cart</button>
            </div>
            @endforeach
        </div>
    </div>
    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            @foreach ($cart as $item)
            @php
                $product = collect($products)->firstWhere('id', $item['product_id']);
                $subtotal = $product['price'] * $item['quantity'];
            @endphp
            <div class="item" data-id="{{ $item['product_id'] }}">
                <div class="image">
                    <img src="{{ $product['image'] }}">
                </div>
                <div class="name">{{ $product['name'] }}</div>
                <div class="price">${{ $product['price'] }}</div>
                <div class="totalPrice">${{ $subtotal }}</div>
                <div class="quantity">
                    <span wire:click="changeQuantityCart({{ $item['product_id'] }}, 'minus')" class="minus">-</span>
                    <input wire:input="updateCartQuantity({{ $item['product_id'] }}, $event.target.value)" class="num" type="number" value="{{ $item['quantity'] }}" min="1">
                    <span wire:click="changeQuantityCart({{ $item['product_id'] }}, 'plus')" class="plus">+</span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="total">
            <p>Total: $<span id="totalAmount">{{ number_format($totalAmount, 2) }}</span></p>
        </div>
        <button class="checkout">Check Out</button>
    </div>

    <script>

    </script>
</div>
