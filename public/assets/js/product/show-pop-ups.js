document.addEventListener('DOMContentLoaded', function () {
    // Open popup
    const archivePopup = document.querySelector('.cd-popup');
    if (archivePopup) {
        function archivePopUp() {
        
            document.querySelectorAll('.cd-popup-trigger').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    archivePopup.classList.remove('fade-out');
                    archivePopup.classList.add('fade-in');
                    archivePopup.classList.remove('hidden');
                });
            });
        
            // Close popup
            document.querySelector('.cd-popup').addEventListener('click', function (event) {
                if (
                    event.target.matches('.cd-popup-close') ||
                    event.target.matches('.no')
                ) {
                    event.preventDefault();
                    archivePopup.classList.add('fade-out');
                    archivePopup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            archivePopup.classList.add('hidden');
                        }
                    }, false);
                    archivePopup.classList.remove('fade-in');
                }
            });
        }
    }
    
    const discountPopup = document.querySelector('.discount-popup');
    if (discountPopup) {
        function discountPopUp() {
        
            document.querySelectorAll('.discount-popup-trigger').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    
    
                    discountPopup.classList.remove('fade-out');
                    discountPopup.classList.add('fade-in');
                    discountPopup.classList.remove('hidden');
                });
            });
        
            // Close popup
            document.querySelector('.discount-popup').addEventListener('click', function (event) {
                if (
                    event.target.matches('.discount-popup-close') ||
                    event.target.matches('.discountCancel')
                ) {
                    event.preventDefault();
                    discountPopup.classList.add('fade-out');
                    discountPopup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            discountPopup.classList.add('hidden');
                        }
                    }, false);
                    discountPopup.classList.remove('fade-in');
                }
            });
        }
    }
    
    
    const savePopupNotif = document.querySelector('.save-popup');
    if (savePopupNotif) {
        function savePopup() {
            /* save pop up fade out*/
            document.querySelector('.save-popup').addEventListener('click', function (event) {
                if (event.target.matches('.save-popup-close')) {
                    event.preventDefault();
                    const popup = this;
                    popup.style.animation = 'fadeOut 0.5s forwards'; 
    
                    // After animation ends, hide the element
                    popup.addEventListener('animationend', function() {
                        popup.style.display = 'none';
                    });
                }
            });
        }
    }

    const salesPopup = document.querySelector('.sales-popup');
    if (salesPopup) {
        function salesChannelsPopup() {
        
            document.querySelectorAll('.sales-channel-popup-trigger').forEach(function (trigger) {
                trigger.addEventListener('click', function (event) {
                    event.preventDefault();
                    salesPopup.classList.remove('fade-out');
                    salesPopup.classList.add('fade-in');
                    salesPopup.classList.remove('hidden');
                });
            });
        
            // Close popup
            document.querySelector('.sales-popup').addEventListener('click', function (event) {
                if (
                    event.target.matches('.sales-popup-close') ||
                    event.target.matches('.no')
                ) {
                    event.preventDefault();
                    salesPopup.classList.add('fade-out');
                    salesPopup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            salesPopup.classList.add('hidden');
                        }
                    }, false);
                    salesPopup.classList.remove('fade-in');
                }
            });
        }
    }

    const createPropPopup = document.querySelector('.create-prop-pop-up');
    if (createPropPopup) {
        function createPropPopUp() {
            const createPropPopupTrigger = document.querySelector('.create-prop-popup-trigger');
            createPropPopupTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                createPropPopup.classList.remove('fade-out');
                createPropPopup.classList.add('fade-in');
                createPropPopup.classList.remove('hidden');
            });
        
            // Close popup
            document.querySelector('.create-prop-pop-up').addEventListener('click', function (event) {
                if (
                    event.target.matches('.create-prop-close') ||
                    event.target.matches('.create-propCancel')
                ) {
                    event.preventDefault();
                    createPropPopup.classList.add('fade-out');
                    createPropPopup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            createPropPopup.classList.add('hidden');
                        }
                    }, false);
                    createPropPopup.classList.remove('fade-in');
                }
            });
        }
    }

    const variationsAddPropPopup = document.querySelector('.variations-add-prop-pop-up');
    if (variationsAddPropPopup) {
        function variationsAddPropPopUp() {
            const variationsAddPropPopupTrigger = document.querySelector('.variations-add-prop-popup-trigger');
            console.log(variationsAddPropPopupTrigger);
            variationsAddPropPopupTrigger.addEventListener('click', function (event) {

                event.preventDefault();
                variationsAddPropPopup.classList.remove('fade-out');
                variationsAddPropPopup.classList.add('fade-in');
                variationsAddPropPopup.classList.remove('hidden');
            });
        
            // Close popup
            document.querySelector('.variations-add-prop-pop-up').addEventListener('click', function (event) {
                if (
                    event.target.matches('.variations-add-prop-close') ||
                    event.target.matches('.variations-add-propCancel')
                ) {
                    event.preventDefault();
                    variationsAddPropPopup.classList.add('fade-out');
                    variationsAddPropPopup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            variationsAddPropPopup.classList.add('hidden');
                        }
                    }, false);
                    variationsAddPropPopup.classList.remove('fade-in');
                }
            });
        }
    }

    if (archivePopup) {
        archivePopUp();
    }

    if (discountPopup) {
        discountPopUp();
    }

    if (salesPopup) {
        salesChannelsPopup();
    }
    
    if (createPropPopup) {
        createPropPopUp();
    }
    if (variationsAddPropPopup) {
        variationsAddPropPopUp();
    }
});
