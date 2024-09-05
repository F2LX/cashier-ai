var a;
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
document.querySelector('.listProduct').style.display = 'none';
document.querySelector('.camera').style.display = 'block';
