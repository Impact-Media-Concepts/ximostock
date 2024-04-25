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
    drag: false,
    focus  : 0,
    omitEnd: true,
    pagination: false
});

let primaryPhotoInput = null;

fileInput.addEventListener('change', handleFileInputChange);

async function handleFileInputChange(event) {
    const files = event.target.files;
    
    if (files.length === 1) {
        await uploadSingleFile(files[0]);
    } else if (files.length > 1) {
        await uploadMultipleFiles(files);
    }
}

//upload a single file
async function uploadSingleFile(file) {
    splideSection.classList.remove('hidden');
    const img = createImageElement(file);
    const li = createSlideElement(img, file.name);
    splideList.appendChild(li);
    splide.refresh();
}

//upload multiple files
async function uploadMultipleFiles(files) {
    splideSection.classList.remove('hidden');
    // Create a document fragment to hold the elements
    const fragment = document.createDocumentFragment();

    for (const file of files) {
        const img = createImageElement(file);
        const li = createSlideElement(img, file.name);
        fragment.appendChild(li);
    }

    splideList.appendChild(fragment);

    splide.refresh();
}

//create image element
function createImageElement(file) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('object-contain', 'select-image', '!w-[16rem]', 'select-none');
    img.addEventListener('click', handleImageClick);
    return img;
}

//create splide slide element
function createSlideElement(img, fileName) {
    const li = document.createElement('li');
    li.classList.add('splide__slide', 'pt-[1.5rem]');
    
    const imgContainer = createImageContainer(img);
    li.appendChild(imgContainer);
    
    const span = document.createElement('span');
    span.style.display = 'flex';
    span.style.justifyContent = 'center';
    span.style.alignItems = 'flex-end';
    span.appendChild(document.createTextNode(fileName));
    li.appendChild(span);
    
    return li;
}

//create image container
function createImageContainer(img) {
    const imgContainer = document.createElement('span');
    imgContainer.classList.add('relative');
    imgContainer.style.width = '254px';
    imgContainer.style.height = '254px';
    imgContainer.style.display = 'flex';
    imgContainer.style.justifyContent = 'center';
    imgContainer.style.overflow = 'hidden';
    
    const removeButton = createRemoveButton();
    imgContainer.appendChild(removeButton);
    imgContainer.appendChild(img);
    
    return imgContainer;
}

//create remove button
function createRemoveButton() {
    const removeButton = document.createElement('button');
    removeButton.classList.add('w-[1.56rem]', 'h-[1.56rem]', 'bg-[#3EABD5]', 'js-remove-button', 'absolute', 'right-0', 'flex', 'items-center', 'justify-center', 'hover:bg-[#3999BE]');
    removeButton.type = 'button';
    removeButton.addEventListener('click', handleRemoveButtonClick);
    
    const removeButtonImg = document.createElement('img');
    removeButtonImg.src = '../images/white-x-icon.png';
    removeButtonImg.alt = 'white x icon';
    removeButtonImg.classList.add('h-[0.68rem]', 'w-[0.68rem]', 'select-none');
    
    removeButton.appendChild(removeButtonImg);
    
    return removeButton;
}

//image click
function handleImageClick() {
    const images = document.querySelectorAll('.select-image');
    images.forEach(image => image.classList.remove('border-[3px]', 'border-[#3EABD5]'));
    this.classList.add('border-[3px]', 'border-[#3EABD5]');
    setPrimary.onclick = handleSetPrimaryClick(this);
}

//remove button click
function handleRemoveButtonClick() {
    const liToRemove = this.closest('.splide__slide');
    liToRemove.remove();
    splide.refresh();
    if (splide.length === 0) {
        splideSection.classList.add('hidden');
    }
}

//primary click
function handleSetPrimaryClick(img) {
    return function() {
        img.classList.add("primary-foto-size");
        const imgContainer = img.parentElement;
        imgContainer.classList.add("primary-span-size");
        const lis = document.querySelectorAll('.splide__slide');
        lis.forEach(li => li.classList.remove('primary-size'));
        const li = imgContainer.parentElement;
        li.classList.add('primary-size');
        
        const primaryPhotoInput = document.querySelector('input[name="primaryPhoto"]');
        if (primaryPhotoInput) {
            primaryPhotoInput.name = 'photos[]';
        }
        const fotoInputTag = imgContainer.querySelector('input[type="file"]');
        if (fotoInputTag) {
            fotoInputTag.name = 'primaryPhoto';
        }
    };
}

document.getElementById('get_file').onclick = function() {
    fileInput.click();
};

splide.mount();
// #endregion
