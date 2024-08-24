const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

// VALIDATION
var checkName = false
var checkEmail = false
var checkPass = false

var captureButton = document.getElementById('capture')
captureButton.disabled = true

document.getElementById('name').addEventListener('input', () => {
    validateName()
})

document.getElementById('email').addEventListener('input', () => {
    validateEmail()
})

document.getElementById('pass').addEventListener('input', () => {
    validatePass()
})

function validateName() {
    var name = document.getElementById('name').value
    var nameError = document.getElementById('nameError')

    if (!name) {
        nameError.textContent = 'Required'
        checkName = false
    } else if (name.length < 2) {
        nameError.textContent = 'Invalid name : must be at least 2 characters'
        checkName = false
    } else {
        nameError.textContent = ''
        checkName = true
    }
    checkSubmit()
}

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

function validatePass() {
    var pass = document.getElementById('pass').value
    var passError = document.getElementById('passError')

    if (!pass) {
        passError.textContent = 'Required'
        checkPass = false
    } else if (isNaN(pass)) {
        passError.textContent = 'Invalid Password : must be number';
        checkPass = false;
    }else if (pass.length != 6) {
        passError.textContent = 'Invalid Password : must be 6 digits long'
        checkPass = false
    }else {
        passError.textContent = ''
        checkPass = true
    }
    checkSubmit()
}

function checkSubmit() {
    if (checkName && checkEmail && checkPass) {
        captureButton.disabled = false;
    } else {
        captureButton.disabled = true;
    }
    checkSubmit()
}

var a;
function clickPass(){
    console.log(a);
    if(a==1){
        document.getElementById('pass').type='password';
        document.getElementById('pass-icon').src='pass-hide.png';
        a=0;
    }else{
        document.getElementById('pass').type='text';
        document.getElementById('pass-icon').src='pass-show.png';
        a=1;
    }
}