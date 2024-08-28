let listProductHTML = document.querySelector('.listProduct');
let listCartHTML = document.querySelector('.listCart');
let iconCart = document.querySelector('.icon-cart');
let iconCartSpan = document.querySelector('.icon-cart span');
let body = document.querySelector('body');
let products = [];
let cart = [];

    const addDataToHTML = () => {
    // remove datas default from HTML
        // add new datas
        if(products.length > 0) // if has data
        {
            products.forEach(product => {
                let newProduct = document.createElement('div');
                newProduct.dataset.id = product.id;
                newProduct.classList.add('item');
                newProduct.innerHTML = 
                `<img src="${product.image}" alt="">
                <h2>${product.name}</h2>
                <div class="price">$${product.price}</div>
                <button class="addCart">Add To Cart</button>`;
                listProductHTML.appendChild(newProduct);
            });
        }
    }
    listProductHTML.addEventListener('click', (event) => {
        let positionClick = event.target;
        if(positionClick.classList.contains('addCart')){
            let id_product = positionClick.parentElement.dataset.id;
            addToCart(id_product);
        }
    })
const addToCart = (product_id) => {
    let positionThisProductInCart = cart.findIndex((value) => value.product_id == product_id);
    if(cart.length <= 0){
        cart = [{
            product_id: product_id,
            quantity: 1
        }];
    }else if(positionThisProductInCart < 0){
        cart.push({
            product_id: product_id,
            quantity: 1
        });
    }else{
        cart[positionThisProductInCart].quantity = cart[positionThisProductInCart].quantity + 1;
    }
    addCartToHTML();
    addCartToMemory();
}
const addCartToMemory = () => {
    localStorage.setItem('cart', JSON.stringify(cart));
}

const updateListCartPadding = () => {
    // Select the listCart element
    const listCart = document.querySelector('.listCart');

    // Count the number of items
    const itemCount = listCart.children.length;

    // Apply padding if item count exceeds 8
    if (itemCount > 8) {
        listCart.style.paddingRight = '10px';
    } else {
        listCart.style.paddingRight = '0'; // Remove padding if less than or equal to 8 items
    }
}

const addCartToHTML = () => {
    listCartHTML.innerHTML = '';
    let totalQuantity = 0;
    let totalAmount = 0;
    if(cart.length > 0){
        cart.forEach(item => {
            totalQuantity = totalQuantity +  item.quantity;
            let newItem = document.createElement('div');
            newItem.classList.add('item');
            newItem.dataset.id = item.product_id;

            let positionProduct = products.findIndex((value) => value.id == item.product_id);
            let info = products[positionProduct];
            let subtotal = info.price * item.quantity; // Calculate subtotal for the item
            totalAmount += subtotal; 
            listCartHTML.appendChild(newItem);
            newItem.innerHTML = `
            <div class="image">
                    <img src="${info.image}">
                </div>
                <div class="name">
                ${info.name}
                </div>
                <div class="price">
                $${info.price}
                </div>
                <div class="totalPrice">$${info.price * item.quantity}</div>
                <div class="quantity">
                    <span class="minus">-</span>
                    <input class="num" type="number" value="${item.quantity}" min="1">
                    <span class="plus">+</span>
                </div>
            `;
        })
    }
    document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
    iconCartSpan.innerText = totalQuantity;
}


listCartHTML.addEventListener('click', (event) => {
    let positionClick = event.target;
    if (positionClick.classList.contains('minus') || positionClick.classList.contains('plus')) {
        let product_id = positionClick.parentElement.parentElement.dataset.id;
        let type = positionClick.classList.contains('plus') ? 'plus' : 'minus';
        changeQuantityCart(product_id, type);
    }
});

listCartHTML.addEventListener('input', (event) => {
    let positionInput = event.target;
    if (positionInput.tagName.toLowerCase() === 'input') {
        let product_id = positionInput.parentElement.parentElement.dataset.id;
        let newQuantity = parseInt(positionInput.value);
        updateCartQuantity(product_id, newQuantity);
    }
});

const changeQuantityCart = (product_id, type) => {
    let positionItemInCart = cart.findIndex((value) => value.product_id == product_id);
    if (positionItemInCart >= 0) {
        let info = cart[positionItemInCart];
        if (type === 'plus') {
            cart[positionItemInCart].quantity += 1;
        } else if (type === 'minus') {
            if (info.quantity > 1) {
                cart[positionItemInCart].quantity -= 1;
            } else {
                cart.splice(positionItemInCart, 1);
            }
        }
    }
    addCartToHTML();
    addCartToMemory();
}

const updateCartQuantity = (product_id, newQuantity) => {
    let positionItemInCart = cart.findIndex((value) => value.product_id == product_id);
    if (positionItemInCart >= 0 && newQuantity > 0) {
        cart[positionItemInCart].quantity = newQuantity;
        addCartToHTML();
        addCartToMemory();
    } else if (newQuantity <= 0) {
        cart.splice(positionItemInCart, 1);
        addCartToHTML();
        addCartToMemory();
    }
}


const initApp = () => {
    // get data product
    fetch('http://127.0.0.1:8000/js/products.json')
    .then(response => response.json())
    .then(data => {
        products = data;
        addDataToHTML();

        // get data cart from memory
        if(localStorage.getItem('cart')){
            cart = JSON.parse(localStorage.getItem('cart'));
            addCartToHTML();
        }
    })
}
initApp();
var a = 1; // Initialize the toggle state

function clickPass(){
    const listProduct = document.querySelector('.listProduct');
    const camera = document.querySelector('.camera');

    if(a === 1){
        document.getElementById('pass-icon').src = 'image/camera.svg'; // Switch to camera icon
        listProduct.style.display = 'grid'; // Show the product list
        camera.style.display = 'none'; // Hide the camera view
        a = 0;
    } else {
        document.getElementById('pass-icon').src = 'image/shelves.png'; // Switch to shelves icon
        listProduct.style.display = 'none'; // Hide the product list
        camera.style.display = 'block'; // Show the camera view
        a = 1;
    }
}

// Ensure initial state is set correctly on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.listProduct').style.display = 'none';
    document.querySelector('.camera').style.display = 'block';
});
