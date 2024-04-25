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
        console.log("single");
        await uploadSingleFile(files[0], event);
    } else if (files.length > 1) {
        console.log("multi");
        await uploadMultipleFiles(files, event);
    }
}

//upload a single file
async function uploadSingleFile(file, event) {
    splideSection.classList.remove('hidden');
    const fotoInputTag = document.createElement('input');

    fotoInputTag.name = 'photos[]';
    fotoInputTag.type = 'file';
    fotoInputTag.classList.add('hidden');

    if (event.target.files[0]) {
        fotoInputTag.files = event.target.files;
    }

    const img = createImageElement(file, fotoInputTag);
    const li = createSlideElement(img, file.name, fotoInputTag);

    splideList.appendChild(li);
    splide.refresh();
}

//upload multiple files
async function uploadMultipleFiles(files, event) {
    splideSection.classList.remove('hidden');
    // Create a document fragment to hold the elements
    const fragment = document.createDocumentFragment();

    for (const file of files) {
        const fotoInputTag = document.createElement('input');
        fotoInputTag.name = 'photos[]';
        fotoInputTag.type = 'file';
        fotoInputTag.classList.add('hidden');
        if (event.target.files[0]) {
            fotoInputTag.files = event.target.files;
        }

        const img = createImageElement(file, fotoInputTag);
        const li = createSlideElement(img, file.name, fotoInputTag);
    
        fragment.appendChild(li);
    }

    splideList.appendChild(fragment);

    splide.refresh();
}

//create image element
function createImageElement(file, fotoInputTag) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('object-contain', 'select-image', '!w-[16rem]', 'select-none');
    
    img.onclick = function (event) {
        handleImageClick(fotoInputTag, event);
    };

    return img;
}

//create splide slide element
function createSlideElement(img, fileName, fotoInputTag) {
    const li = document.createElement('li');
    li.classList.add('splide__slide', 'pt-[1.5rem]');
    
    const imgContainer = createImageContainer(img, fotoInputTag);
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
function handleImageClick(fotoInputTag, event) {
    const images = document.querySelectorAll('.select-image');
    images.forEach(image => image.classList.remove('border-[3px]', 'border-[#3EABD5]'));
    const f = event.target;
    f.classList.add('border-[3px]', 'border-[#3EABD5]');
    setPrimary.onclick = handleSetPrimaryClick(f, fotoInputTag);

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
function handleSetPrimaryClick(img, fotoInputTag) {
    return function() {
        if (img.classList.contains("primary-photo-size")) {
            unsetPrimaryPhoto(img);
        } else {
            img.classList.add("primary-photo-size");
            const imgContainer = img.parentElement;
            const liContainer = imgContainer.parentElement;
            console.log("Li container: ", liContainer);
            imgContainer.classList.add("primary-span-size");
            const lis = document.querySelectorAll('.splide__slide');
            lis.forEach(li => li.classList.remove('primary-size'));
            const li = imgContainer.parentElement;
            li.classList.add('primary-size');
            console.log(" imgContainer", imgContainer);
            console.log(" li", li);
            fotoInputTag.name = 'primaryPhoto';
            console.log("primaryPhotoInput Before:", primaryPhotoInput);
            if (primaryPhotoInput && primaryPhotoInput !== fotoInputTag) {
                primaryPhotoInput.name = 'photos[]';
            }
            
            primaryPhotoInput = fotoInputTag;
            console.log("primaryPhotoInput After:", primaryPhotoInput);
        }
    };
}

// function unsetPrimaryPhoto(img) {
//     console.log("unsetprimaryPhoto function", img);
//     img.classList.remove("primary-photo-size");
//     const imgContainer = img.parentElement;
//     imgContainer.classList.remove("primary-span-size");
//     const lis = document.querySelectorAll('.splide__slide');
//     lis.forEach(li => li.classList.remove('primary-size'));
//     if (primaryPhotoInput && primaryPhotoInput !== fotoInputTag) {
//         primaryPhotoInput.name = 'photos[]';
//         console.log("primaryPhotoInput if not primary:", primaryPhotoInput);
//     }
// }

document.getElementById('get_file').onclick = function() {
    fileInput.click();
};

splide.mount();
// #endregion
