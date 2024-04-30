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
const fileInput = document.getElementById('uploadPhoto');
const splideList = document.querySelector('.splide__list');
const splideSection = document.getElementById('splideSection');
const setPrimary = document.getElementById('setPrimaryPhoto');

// Initialize Splide
const splide = new Splide('.splide', {
    perPage: 5,
    perMove: 1,
    drag: false,
    focus  : 0,
    omitEnd: true,
    pagination: false
});

// Initialize primary photo input variable
let primaryPhotoInput = null;

// Function to handle file input change
fileInput.addEventListener('change', function(event) {
    const files = event.target.files;

    if (files && files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const acceptedExtensions = ['jpg', 'jpeg', 'png'];

            if (!acceptedExtensions.includes(fileExtension)) {
                // Clear the file input
                fileInput.value = null;
                alert('Only [] files are allowed.');
                return; // Stop if invalid file type is found
            }
        }

        // code goes further if file type is valid
        splideSection.classList.remove('hidden');

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const li = createSlideElement(file);
            splideList.appendChild(li);
        }

        splide.refresh();
    }
});

function createImageContainer(img, fotoInputTag) {
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
    imgContainer.appendChild(fotoInputTag);

    return imgContainer;
}

function createSlideElement(file) {
    const li = document.createElement('li');
    li.classList.add('splide__slide', 'pt-[1.5rem]');

    const img = createImageElement(file);
    const fotoInputTag = createPhotoInputTag([file]); // Pass an array with a single file
    const imgContainer = createImageContainer(img, fotoInputTag[0]);
    const span = createSpanElement(file.name);

    img.addEventListener('click', function() {
        handleImageClick(img, li, fotoInputTag[0]); // Use the first element of the array
    });
    
    li.appendChild(imgContainer);
    li.appendChild(span);

    return li;
}

function createImageElement(file) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('object-contain', 'select-image', '!w-[16rem]', 'select-none');
    return img;
}

function createRemoveButton() {
    const removeButton = document.createElement('button');
    removeButton.classList.add('w-[1.56rem]', 'h-[1.56rem]', 'bg-[#3EABD5]', 'js-remove-button', 'absolute', 'right-0', 'flex', 'items-center', 'justify-center', 'hover:bg-[#3999BE]');
    removeButton.type = 'button';
    removeButton.innerHTML = '<img src="../images/white-x-icon.png" alt="white x icon" class="h-[0.68rem] w-[0.68rem] select-none">';
    removeButton.addEventListener('click', function() {
        const liToRemove = this.closest('.splide__slide');
        liToRemove.remove();
        splide.refresh();

        if (splide.length === 0) {
            splideSection.classList.add('hidden');
        }
    });
    return removeButton;
}

// Function to create a photo input tag
function createPhotoInputTag(files) {
    const fotoInputTags = [];

    for (let i = 0; i < files.length; i++) {
        const fotoInputTag = document.createElement('input');
        fotoInputTag.name = 'photos[]';
        fotoInputTag.type = 'file';
        fotoInputTag.classList.add('hidden');

        const fileList = new DataTransfer();
        fileList.items.add(files[i]);
        fotoInputTag.files = fileList.files;

        fotoInputTags.push(fotoInputTag);
    }

    return fotoInputTags;
}

// Function to handle click on an image
function handleImageClick(img, li, fotoInputTag) {
    const images = document.querySelectorAll('.select-image');
    images.forEach(image => {
        image.classList.remove('border-[3px]', 'border-[#3EABD5]');
    });

    img.classList.add('border-[3px]', 'border-[#3EABD5]');

    setPrimary.onclick = function() {
        togglePrimaryPhoto(img, li, fotoInputTag);
    };
}

function togglePrimaryPhoto(img, li, fotoInputTag) {
    const isPrimary = fotoInputTag.name === 'primaryPhoto';
    if (!isPrimary) {
        img.classList.add("primary-photo-size");
        const imgContainer = img.parentElement;
        imgContainer.classList.add("primary-span-size");
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
    } else {
        img.classList.remove("primary-photo-size");
        li.classList.remove('primary-size');

        fotoInputTag.name = 'photos[]';
        primaryPhotoInput = null;
    }
}

function createSpanElement(fileName) {
    const span = document.createElement('span');
    span.style.display = 'flex';
    span.style.justifyContent = 'center';
    span.style.alignItems = 'flex-end';
    const fileNameNode = document.createTextNode(fileName);

    span.appendChild(fileNameNode);
    return span;
}

// Function to open file input dialog
document.getElementById('get_file').onclick = function() {
    fileInput.click();
};

// Mount Splide
splide.mount();
