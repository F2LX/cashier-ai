const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
container.classList.add("sign-up-mode");
triggerStream();
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
        passError.textContent = 'Invalid Password : must be number'
        checkPass = false
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
        document.getElementById('pass-icon').src='image/pass-hide.png';
        a=0;
    }else{
        document.getElementById('pass').type='text';
        document.getElementById('pass-icon').src='image/pass-show.png';
        a=1;
    }
}

const video = document.getElementById('video');

// Get access to the camera
function triggerStream()
{
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(err => {
        console.error("Error accessing the camera: ", err);
    });
}
function captureFormSubmit() {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/jpeg');

    // Convert data URL to Blob
    const byteString = atob(dataUrl.split(',')[1]);
    const mimeString = dataUrl.split(',')[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    const blob = new Blob([ab], { type: mimeString });

    // Create a File object
    const file = new File([blob], 'captured-image.jpeg', { type: mimeString });

    // Create a DataTransfer object and set the file
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);

    // Get the hidden file input element
    const hiddenFileInput = document.getElementById('hiddenFileInput');
    hiddenFileInput.files = dataTransfer.files;
    console.log(hiddenFileInput[0]);
    // Submit the form
    document.getElementById('captureForm').submit();
}