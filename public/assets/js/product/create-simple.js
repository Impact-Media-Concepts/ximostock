// #region create index 
const createInputFields = document.querySelectorAll('input');
createInputFields.forEach(input => {
    input.addEventListener('keydown', function(event) {
        if (!input.classList.contains('allow-enter') && event.key === 'Enter') {
            event.preventDefault();
        }
    });
});


const steps = document.querySelectorAll('.step');
const createButtonContainer = document.getElementById("createButtonContainer");
let currentStep = 0;

const prevBtns = document.querySelectorAll('.prevBtn');
const nextBtns = document.querySelectorAll('.nextBtn');
const prevBtnId = document.getElementById('prevBtn');
const nextBtnId = document.getElementById('nextBtnId');
const headerNextBtnId = document.getElementById('headerNextBtnId');

function showStep(stepIndex) {
    steps.forEach((step, index) => {
        if (index === stepIndex) {
            step.style.display = 'block';
        } else {
            step.style.display = 'none';
        }
    });

    if (stepIndex === steps.length - 1) {
        nextBtnId.style.display = 'none';
    } else {
        nextBtnId.style.display = 'inline-block';
    }
    
    if (stepIndex === 0) {
        prevBtnId.style.display = 'none';
    } else {
        prevBtnId.style.display = 'inline-block';
    }

    if (stepIndex === 5) {
        document.getElementById('saveBtn').style.display = 'inline-block';
    } else {
        document.getElementById('saveBtn').style.display = 'none';
    }

    // Update progress bar
    const progressBar = document.getElementById('progress');
    const progressPercentage = (stepIndex / (steps.length - 1)) * 100;
    progressBar.style.width = progressPercentage + '%';

    // Display current step
    const currentStepText = document.getElementById('currentStep');
    currentStepText.textContent = `Stap ${stepIndex + 1} van ${steps.length}`;
}

prevBtns.forEach((prevBtn) => { 
    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
})

nextBtns.forEach((nextBtn) => { 
    nextBtn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });
})

showStep(currentStep);
//#endregion

// #region step 1 general
window.allowBackorders = [
    { data: 1, name: 'Ja'},
    { data: 0, name: 'Nee'}
];

window.communicateToChannel = [
    { data: 1, name: 'Ja'},
    { data: 0, name: 'Nee'}
];
//#endregion

// #region step 4 variations

// #endregion
