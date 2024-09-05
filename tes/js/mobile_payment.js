// Desktop and mobile payment check flags
var checkPayment = false;
var submitOrderButton = document.getElementById('pay');
submitOrderButton.disabled = true;
// Handle payment method selection in mobile view
const menuPayment = document.querySelector('.payment-method-mobile');
const paymentBtn = menuPayment.querySelector('#select-btn');
const options = menuPayment.querySelectorAll('.mobile-opt');
const btnText = menuPayment.querySelector('.btn-text');
const selectImg = menuPayment.querySelector('#select-img');

paymentBtn.addEventListener('click', () => {
    menuPayment.classList.toggle('active');
});

options.forEach(option => {
    option.addEventListener('click', () => {
        let selectedBtn = option.querySelector('span').innerText;
        btnText.innerText = selectedBtn;

        while (selectImg.firstChild) {
            selectImg.removeChild(selectImg.firstChild);
        }

        var newImage = document.createElement('img');
        newImage.src = option.querySelector('img').src;
        selectImg.appendChild(newImage);

        checkPayment = true;

        if (option.id === 'card-mobile-btn') {
            console.log("masuk")
            checkPayment = false;
            togglePopUp(); // Open the card input popup
            checkInputs();
        }

        checkSubmitOrder();
        menuPayment.classList.remove('active');
    });
});

// Check if the order can be submitted (for both desktop and mobile)
function checkSubmitOrder() {
    if (checkPayment) {
        submitOrderButton.disabled = false;
    } else {
        submitOrderButton.disabled = true;
    }
}

document.getElementById("pay").addEventListener("click", function() {
    if (checkPayment) {
        setTimeout(function() {
            window.location.href = "mobile_home.html";
        }, 1000); // 1 second delay (1000 milliseconds)
    } else {
        console.log("Button is not active"); // Debugging log
    }
});
  