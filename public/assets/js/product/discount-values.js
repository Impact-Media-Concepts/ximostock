function restrictNumberInput(event) {
    let roundedInput = event.target;
    let value = parseInt(roundedInput.value, 10);
    let min = 10;
    let max = 100;

    if (isNaN(value)) {
        roundedInput.value = "";
        event.preventDefault();
    }
    else if (value > max) {
        roundedInput.value = max;
        event.preventDefault();
    }
}

let discountRoundedInput = document.getElementById("discountRoundedInput");
discountRoundedInput.addEventListener("input", restrictNumberInput);


function decimalRestrictNumberInput(event) {
    let decimalInput = event.target;
    let decimalValue = parseInt(decimalInput.value, 10);
    let decimalMin = 10;
    let decimalMax = 99;

    if (isNaN(decimalValue)) {
        decimalInput.value = "";
        event.preventDefault();
    }
    else if (decimalValue > decimalMax) {
        decimalInput.value = decimalMax;
        event.preventDefault();
    }
}

let discountDecimalsInput = document.getElementById("discountDecimalsInput");
discountDecimalsInput.addEventListener("input", decimalRestrictNumberInput);
