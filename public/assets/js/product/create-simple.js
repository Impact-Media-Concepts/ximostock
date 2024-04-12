// #region create index 
const steps = document.querySelectorAll('.step');
let currentStep = 0;

function showStep(stepIndex) {
    steps.forEach((step, index) => {
        if (index === stepIndex) {
            step.style.display = 'block';
        } else {
            step.style.display = 'none';
        }
    });

    if (stepIndex === 0) {
        document.getElementById('prevBtn').style.display = 'none';
    } else {
        document.getElementById('prevBtn').style.display = 'inline-block';
    }

    if (stepIndex === steps.length - 1) {
        document.getElementById('nextBtn').style.display = 'none';
    } else {
        document.getElementById('nextBtn').style.display = 'inline-block';
    }

    // Update progress bar
    const progressBar = document.getElementById('progress');
    const progressPercentage = (stepIndex / (steps.length - 1)) * 100;
    progressBar.style.width = progressPercentage + '%';

    // Display current step
    const currentStepText = document.getElementById('currentStep');
    currentStepText.textContent = `Step ${stepIndex + 1} of ${steps.length}`;
}

document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
});

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
});

showStep(currentStep);
//#endregion


// #region step 2 photos
const fileInput = document.getElementById('hiddenFileInput');
const previewContainer = document.getElementById('previewContainer');
const splideList = document.querySelector('.splide__list');
const splideSection = document.getElementById('splideSection');

let addButton = document.querySelector('.js-add-button');
let removeButtonsss = document.querySelector('.js-remove-button');
let huts = true;
let splide = new Splide( '.splide', {
    perPage: 5,
    perMove: 1,
    drag: false
});

fileInput.addEventListener('change', function(event) {
    // Get the selected file
    const file = event.target.files[0];

    if (file) {
        
        splideSection.classList.remove("hidden");

        const img = document.createElement('img');

        img.src = URL.createObjectURL(file);
        img.classList.add("object-contain", "select-image");

        const li = document.createElement('li');
        li.classList.add('splide__slide');

        const imgContainer = document.createElement('span');
        const removeButton = document.createElement('button');
        removeButton.classList.add("w-[1.56rem]","h-[1.56rem]", "bg-[#3EABD5]", "js-remove-button");
        removeButton.value = "byebye";
        removeButton.type = "button";

        removeButton.addEventListener('click', function() {

            // Find the parent li of the clicked remove button and remove it
            const liToRemove = this.closest('.splide__slide');
            liToRemove.remove();
            
            // Refreshes the splide so it can know if there are none left
            splide.refresh();

            if (splide.length === 0) {
                console.log(splide.length);
                splideSection.classList.add("hidden");
            }
        });

        imgContainer.appendChild(removeButton);
        imgContainer.appendChild(img);

        li.appendChild(imgContainer);

        img.addEventListener('click', function() {
            const images = document.querySelectorAll('.select-image');
            images.forEach(image => {
                image.classList.remove("border-[3px]", "border-[#3EABD5]");
            });
          
            img.classList.add("border-[3px]", "border-[#3EABD5]");
            fileInput.name = "primaryPhoto"
            console.log(fileInput);
        });

        const span = document.createElement('span');
        span.style.height = "100%";
        span.style.display = "flex";
        span.style.justifyContent = "center";
        span.style.alignItems = "flex-end";
        span.style.marginBottom = "1rem";
        const fileNameNode = document.createTextNode(file.name);

        span.appendChild(fileNameNode);
        li.appendChild(span);
        
        splideList.appendChild(li);

        splide.refresh();
    }
});

document.getElementById('get_file').onclick = function() {
    fileInput.click();
};

splide.mount();

// #endregion