const inputs = document.querySelectorAll("input");
const verifyButton = document.getElementById("verifyButton");
const pinPad = document.querySelector(".pin-pad");

let keyMapping = {}; // Initialize an empty object to store the key mappings

const generatePinPad = () => {
  const numbers = Array.from({ length: 10 }, (_, i) => i); // [0, 1, ..., 9]
  numbers.sort(() => Math.random() - 0.5); // Shuffle the numbers array

  const layout = [
    ['1', '2', '3'], // Top row
    ['q', 'w', 'e'], // Middle row
    ['a', 's', 'd'], // Bottom row
    ['z', 'x', 'c']  // Control row
  ];

  layout.flat().forEach((key, index) => {
    if (index < 10) { // Map first 10 keys to random numbers
      keyMapping[key] = numbers[index];
    } else if (key === 'x') { // Map 'x' to backspace
      keyMapping[key] = 'backspace';
    } else if (key === 'c') { // Map 'c' to clear
      keyMapping[key] = 'clear';
    }
  });

  numbers.forEach((number, index) => {
    const numberButton = document.createElement("button");
    numberButton.textContent = number;
    numberButton.setAttribute('data-index', index);

    numberButton.addEventListener("click", () => {
      const currentInput = Array.from(inputs).find((input) => input.value === "");
      if (currentInput) {
        currentInput.value = number;
        const nextInput = currentInput.nextElementSibling;
        if (nextInput) {
          nextInput.removeAttribute("disabled");
          nextInput.focus();
        }
        checkButtonState();
      }
    });
    pinPad.appendChild(numberButton);
  });

  // Create Clear and Backspace buttons
  const backspaceButton = document.createElement("button");
  backspaceButton.innerHTML = '<i class="fa-solid fa-arrow-left-long" style="color: #ffffff;"></i>';
  backspaceButton.classList.add('backspace');
  backspaceButton.setAttribute('data-index', numbers.length + 1);
  backspaceButton.addEventListener("click", handleBackspace);
  pinPad.appendChild(backspaceButton);

  const clearButton = document.createElement("button");
  clearButton.textContent = 'C';
  clearButton.classList.add('clear');
  clearButton.setAttribute('data-index', numbers.length);
  clearButton.addEventListener("click", () => {
    inputs.forEach(input => {
      input.value = "";
      input.setAttribute("disabled", true);
    });
    inputs[0].removeAttribute("disabled");
    inputs[0].focus();
    checkButtonState();
  });
  pinPad.appendChild(clearButton);
};

const handleBackspace = () => {
  let foundNonEmpty = false;
  
  // Traverse inputs from right to left
  for (let i = inputs.length - 1; i >= 0; i--) {
    if (inputs[i].value !== "") {
      inputs[i].value = "";
      if (i > 0) {
        // Focus on the previous input
        inputs[i - 1].removeAttribute("disabled");
        inputs[i - 1].focus();
      }
      // Disable all subsequent inputs
      for (let j = i + 1; j < inputs.length; j++) {
        inputs[j].value = "";
        inputs[j].setAttribute("disabled", true);
      }
      foundNonEmpty = true;
      break;
    }
  }
  
  // If no non-empty input was found, enable the first input and focus
  if (!foundNonEmpty) {
    inputs[0].removeAttribute("disabled");
    inputs[0].focus();
  }
  
  checkButtonState();
};

const triggerButtonClick = (key) => {
  const mappedValue = keyMapping[key.toLowerCase()];
  if (mappedValue !== undefined) {
    const button = Array.from(pinPad.children).find(btn => btn.textContent === String(mappedValue) || btn.classList.contains(mappedValue));
    if (button) {
      button.click();
    }
  }
};

const checkButtonState = () => {
  const allFilled = Array.from(inputs).every(input => input.value !== "");
  
  if (allFilled) {
    verifyButton.classList.add("active");
    verifyButton.removeAttribute("disabled");
    verifyButton.style.cursor = 'pointer'; // Change cursor to pointer
  } else {
    verifyButton.classList.remove("active");
    verifyButton.setAttribute("disabled", "true");
    verifyButton.style.cursor = 'not-allowed'; // Change cursor to not-allowed
  }

  // Debugging log
  console.log("Button active:", verifyButton.classList.contains("active"));
};


// Call generatePinPad on window load
window.addEventListener("load", () => {
  inputs[0].focus();
  generatePinPad();
});

document.addEventListener('keydown', (e) => {
  const key = e.key.toLowerCase();
  if (key === "backspace") {
    e.preventDefault(); // Prevent default backspace behavior
    handleBackspace();
  } else if (keyMapping[key] !== undefined || (key >= '0' && key <= '9')) {
    e.preventDefault(); // Prevent the default input behavior for all number keys
    triggerButtonClick(key);
  }
});

document.getElementById("verifyButton").addEventListener("click", function() {
  console.log("Button clicked"); // Debugging log
  if (verifyButton.classList.contains("active")) {
    console.log("Redirecting to products.html"); // Debugging log
    window.location.href = "products.html";
  } else {
    console.log("Button is not active"); // Debugging log
  }
});

document.querySelector('form').addEventListener('submit', function(e) {
  e.preventDefault(); // Prevent the form from submitting
});
