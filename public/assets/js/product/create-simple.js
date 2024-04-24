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
    pagination: false,
    width: '89rem'
});

let primaryPhotoInput = null;

fileInput.addEventListener('change', function(event) {
    const files = event.target.files;
    
    // Check if only one file is selected
    if (files.length === 1) {
        uploadSingleFile(files, event)
    } else if (files.length > 1) {

        uploadMultipleFiles(files, event)
    }
});

async function uploadSingleFile(files, event) {
    // Single file upload logic
    console.log("1 file");
    const file = files[0];
    splideSection.classList.remove('hidden');
    
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('object-contain', 'select-image', '!w-[16rem]', 'select-none');

    const li = document.createElement('li');
    li.classList.add('splide__slide', 'pt-[1.5rem]');

    const imgContainer = document.createElement('span');
    imgContainer.classList.add('relative');
    imgContainer.style.width = '254px';
    imgContainer.style.height = '254px';
    imgContainer.style.display = 'flex';
    imgContainer.style.justifyContent = 'center';
    imgContainer.style.overflow = 'hidden';

    const removeButtonImg = document.createElement('img');
    removeButtonImg.src = '../images/white-x-icon.png';
    removeButtonImg.alt = 'white x icon';
    removeButtonImg.classList.add('h-[0.68rem]', 'w-[0.68rem]', 'select-none');

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
            img.classList.add("primary-foto-size");
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
        };
    });

    const span = document.createElement('span');
    span.style.display = 'flex';
    span.style.justifyContent = 'center';
    span.style.alignItems = 'flex-end';
    const fileNameNode = document.createTextNode(file.name);

    span.appendChild(fileNameNode);
    li.appendChild(span);

    splideList.appendChild(li);
    splide.refresh();
}

async function uploadMultipleFiles(files, event) {
    
    for (const file of files) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('object-contain', 'select-image', '!w-[16rem]', 'select-none');

        const li = document.createElement('li');
        li.classList.add('splide__slide', 'pt-[1.5rem]');

        const imgContainer = document.createElement('span');
        imgContainer.classList.add('relative');
        imgContainer.style.width = '254px';
        imgContainer.style.height = '254px';
        imgContainer.style.display = 'flex';
        imgContainer.style.justifyContent = 'center';
        imgContainer.style.overflow = 'hidden';

        const removeButtonImg = document.createElement('img');
        removeButtonImg.src = '../images/white-x-icon.png';
        removeButtonImg.alt = 'white x icon';
        removeButtonImg.classList.add('h-[0.68rem]', 'w-[0.68rem]', 'select-none');

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
                img.classList.add("primary-foto-size");
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
            };
        });

        const span = document.createElement('span');
        span.style.display = 'flex';
        span.style.justifyContent = 'center';
        span.style.alignItems = 'flex-end';
        const fileNameNode = document.createTextNode(file.name);

        span.appendChild(fileNameNode);
        li.appendChild(span);

        splideList.appendChild(li);
        splide.refresh();
    }
}

document.getElementById('get_file').onclick = function() {
    fileInput.click();
};

splide.mount();
// #endregion
