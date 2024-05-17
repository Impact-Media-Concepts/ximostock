document.addEventListener('DOMContentLoaded', function () {
    function handleNotifPopups(popupClass) {
        const popupNotif = document.querySelector(popupClass);
        if (popupNotif) {
            popupNotif.addEventListener('click', function(event) {
                if (event.target.matches(`${popupClass}-close`)) {
                    event.preventDefault();
                    popupNotif.style.animation = 'fadeOut 0.3s forwards'; 
                    
                    // After animation ends, hide the element
                    popupNotif.addEventListener('animationend', function() {
                        popupNotif.style.display = 'none';
                    }, { once: true });
                }
            });
        }
    }
    
    handleNotifPopups('.save-popup');
    handleNotifPopups('.error-popup');
    
    //function handlePopup (parameters) = class on container element of popup, class of button you want to click to open popup, classes of buttons/elements you want to close the popup with
    function handlePopup(popupSelector, triggerSelector, closeSelectors) {
        const popup = document.querySelector(popupSelector);
        const trigger = document.querySelector(triggerSelector);
        
        if (popup && trigger) {
            trigger.addEventListener('click', function(event) {
                event.preventDefault();
                popup.classList.remove('fade-out', 'hidden');
                popup.classList.add('fade-in');
            });
            
            popup.addEventListener('click', function(event) {
                if (closeSelectors.some(selector => event.target.matches(selector))) {
                    event.preventDefault();
                    popup.classList.add('fade-out');
                    popup.addEventListener('animationend', function(event) {
                        if (event.animationName === 'fadeOut') {
                            popup.classList.add('hidden');
                        }
                    }, { once: true });
                    popup.classList.remove('fade-in');
                }
            });
        }
    }
    
    handlePopup('.variations-add-prop-pop-up', '.variations-add-prop-popup-trigger', ['.variations-add-prop-close', '.variations-add-propCancel']);
    handlePopup('.create-prop-pop-up', '.create-prop-popup-trigger', ['.create-prop-close', '.create-prop-cancel']);
    handlePopup('.sales-popup', '.sales-channel-popup-trigger', ['.sales-popup-close', '.no']);
    handlePopup('.discount-popup', '.discount-popup-trigger', ['.discount-popup-close', '.discountCancel']);
    handlePopup('.cd-popup', '.cd-popup-trigger', ['.cd-popup-close', '.no']);
});
