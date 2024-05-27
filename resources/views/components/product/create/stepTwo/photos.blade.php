{{-- Photos --}}
<!-- Todo: scaling of splide js for screensizes -->
<!-- sets url path for img -->
<?php
    $app_url = env('VITE_APP_URL');
?>

<style>
    .primary-size {
        width: 20rem !important;
        height: 20rem !important;
    }
    
    .primary-photo-size {
        width: unset !important;
    }
    
    .primary-span-size {
        width: 350px !important;
        height: 350px !important;
        display: flex !important;
        justify-content: center !important;
        overflow: hidden !important;
    }
    
    /* .splide__arrow {
        background: #3DABD5;
        width: 2em !important;
        height: 2em !important;
    }
    .splide__arrow--prev {
        left: 0.7em !important;
    }
    
    .splide__arrow--prev svg {
        width: 1.2em !important;
        height: 1.2em !important;
    } */
    
    @media only screen and (min-width: 1280px) {
        .splide {
            max-width: 94rem !important;
        }
    }
    
    @media only screen and (min-width: 1920px) {
        .splide {
            max-width: 94rem !important;
        }
    }
    
    @media only screen and (min-width: 2560px) {
        .splide {
            max-width: 134rem !important;
        }
    }
</style>

<div class="bg-white rounded-t-lg basic:h-[38rem] hd:h-[50rem] uhd:h-[57rem] create-container-border">
    <div class="h-[4.56rem] flex flex-col gap-[0.5rem] rounded-t-lg" style="border: 1px solid #F0F0F0;">
        <div class="w-full ml-[1.56rem] mt-[0.6rem]">
            <p class="font-bold text-[18px] text-[#717171]">Lorem, ipsum dolor.</p>
            <p class="text-[14px]">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea autem corrupti officia provident, maxime distinctio!</p>
        </div>
    </div>
    
    <div class="flex justify-center items-center w-full mt-[1.5rem]">
        <div class="flex flex-col justify-between items-center basic:w-[67rem] basic:h-[32rem] hd:h-[44rem] hd:w-[94rem] uhd:h-[50.5rem] uhd:w-[134rem] rounded-md" style="border: 1px solid #F0F0F0;">
            <div class="basic:w-[67rem] hd:w-[94rem] uhd:w-[134rem] rounded-md">
                <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                    <p class="ml-[1.37rem] text-[14px] text-[#fff]">Foto's instellen</p>
                </div>
                
                <section class="splide hidden rounded-md flex hd:justify-start uhd:justify-center" id="splideSection" aria-label="Splide Basic HTML Example">
                    <div class="splide__track hd:ml-[3rem] hd:w-[89rem] uhd:w-[124rem] uhd:!ml-0">
                        <ul class="splide__list uhd:!w-[119rem]" id="splideList"></ul>
                    </div>
                </section>
            </div>
            
            <div class="flex w-full justify-start items-end gap-[0.5rem] mb-[1.5rem] ml-[2rem]">
                <button
                    class="hover:bg-[#3999BE] js-add-button hover:cursor-pointer text-left text-[14px] text-white duration-100 flex items-center justify-center z-20 w-[14.06rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid white"
                    type="button"
                    id="get_file"
                >
                    Voeg foto toe
                    <div class="flex items-center justify-center">
						<!-- TODO: add accepted file types -->
                        <input class="hidden" type="file" accept=".jpg, .jpeg, .png" id="uploadPhoto" class="hidden" multiple/>
                    </div>
                </button>
                
                <button
                    class="hover:bg-[#3999BE] duration-100 flex items-center justify-center z-20 w-[14.06rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid white"
                    type="button"
                    id="setPrimaryPhoto">
                    <div class="flex items-center justify-center">
                        <span class="text-center text-[14px] text-white">Zet als primair</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
        removeButton.innerHTML = `<img src="{{ $app_url }}/images/white-x-icon.png" alt="white x icon" class="h-[0.68rem] w-[0.68rem] select-none">`;
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
</script>
