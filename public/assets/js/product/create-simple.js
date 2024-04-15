// #region create index 
const steps = document.querySelectorAll('.step');
const createButtonContainer = document.getElementById("createButtonContainer");
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

// #region step 2 photos
const fileInput = document.getElementById('uploadFoto');
const previewContainer = document.getElementById('previewContainer');
const splideList = document.querySelector('.splide__list');
const splideSection = document.getElementById('splideSection');
const setPrimary = document.getElementById('setPrimaryPhoto');

let addButton = document.querySelector('.js-add-button');
let removeButtonsss = document.querySelector('.js-remove-button');

let splide = new Splide( '.splide', {
    perPage: 5,
    perMove: 1,
    drag: false
});

let primaryPhotoInput = null;

fileInput.addEventListener('change', function(event) {
    // Get the selected file
    const file = event.target.files[0];

    if (file) {
        splideSection.classList.remove('hidden');

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('object-contain', 'select-image', '!w-[16rem]');

        const li = document.createElement('li');
        li.classList.add('splide__slide', 'pt-[1.5rem]');

        const imgContainer = document.createElement('span');
        imgContainer.classList.add('relative');

        const removeButtonImg = document.createElement('img');
        removeButtonImg.src = '../images/white-x-icon.png';
        removeButtonImg.alt = 'white x icon';
        removeButtonImg.classList.add('h-[0.68rem]', 'w-[0.68rem]');

        const removeButton = document.createElement('button');
        removeButton.classList.add('w-[1.56rem]','h-[1.56rem]', 'bg-[#3EABD5]', 'js-remove-button', 'absolute', 'right-0', 'flex', 'items-center', 'justify-center', 'hover:bg-[#3999BE]');
        removeButton.type = 'button';
        removeButton.addEventListener('click', function() {
            const liToRemove = this.closest('.splide__slide');
            liToRemove.remove();
            splide.refresh();

            if (splide.length === 0) {
                splideSection.classList.add('hidden');
            }
        });

        removeButton.appendChild(removeButtonImg);


        const fotoInputTag = document.createElement('input');
        fotoInputTag.name = 'photos[]';
        fotoInputTag.type = 'file';
        fotoInputTag.classList.add('hidden');

        if (event.target.files[0]) {
            fotoInputTag.files = event.target.files;
        }

        imgContainer.appendChild(removeButton);
        imgContainer.appendChild(img);
        imgContainer.appendChild(fotoInputTag);

        li.appendChild(imgContainer);

        img.addEventListener('click', function() {
            const images = document.querySelectorAll('.select-image');
            images.forEach(image => {
                image.classList.remove('border-[3px]', 'border-[#3EABD5]');
            });

            img.classList.add('border-[3px]', 'border-[#3EABD5]');

            setPrimary.onclick = function() {
                img.classList.add("primary-foto-size")
                const lis = document.querySelectorAll('.splide__slide');
                lis.forEach(li => {
                    li.classList.remove('primary-size');
                });
                li.classList.add('primary-size');
    
                fotoInputTag.name = 'primaryPhoto';
    
                if (primaryPhotoInput && primaryPhotoInput !== fotoInputTag) {
                    primaryPhotoInput.name = 'photos[]';
                }

                primaryPhotoInput = fotoInputTag;
            };
        });

        const span = document.createElement('span');
        span.style.height = '100%';
        span.style.display = 'flex';
        span.style.justifyContent = 'center';
        span.style.alignItems = 'flex-end';
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
