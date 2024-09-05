window.onload = function() {
    // Tampilkan logo selama 3 detik
    setTimeout(function() {
        // Sembunyikan logo
        document.getElementById('logo-container').style.display = 'none';
        // Tampilkan konten utama
        document.getElementById('container').style.display = 'block';
    }, 1000); // 3000 ms = 3 detik
  }

var checkEmail = false;
var loginButton = document.getElementById('login')
loginButton.disabled = true

document.getElementById('email').addEventListener('input', () => {
    validateEmail()
});

function validateEmail(){
    var email = document.getElementById('email').value
    var emailError = document.getElementById('emailError')
    let ca = 0
    let cd = 0
    const ai = email.indexOf('@')
    const di = email.indexOf('.')
    for (let i = 0; i < email.length; i++) {
        if(email[i] == '@') ca++;
        else if(email[i] == '.') cd++;
    }

    if(!email){
        emailError.textContent = 'Required'
        checkEmail = false
    }else if(ai == -1 || di == -1){
        emailError.textContent = 'Invalid email : must contain @ and .'
        checkEmail = false
    }else if (ca>1 || cd>1){
        emailError.textContent = 'Invalid email : only contain 1 @ and 1 .'
        checkEmail = false
    }else {
        emailError.textContent = ''
        checkEmail = true
    }
    checkSubmit()
}

function checkSubmit() {
    if (checkEmail) {
        loginButton.disabled = false;
    } else {
        loginButton.disabled = true;
    }
    checkSubmit()
}


document.getElementById("login").addEventListener("click", function() {
    console.log("Button clicked"); // Debugging log
    if (checkEmail) {
        setTimeout(function() {
            window.location.href = "mobile_pin.html";
        }, 1000); // 1 second delay (1000 milliseconds)
    } else {
        console.log("Button is not active"); // Debugging log
    }
});
  