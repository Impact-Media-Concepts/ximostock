document.addEventListener("DOMContentLoaded", function () {
    // Open popup
    function archivePopUp() {
        const archivePopup = document.querySelector(".cd-popup");
        document.querySelectorAll(".cd-popup-trigger").forEach(function (trigger) {
            trigger.addEventListener("click", function (event) {
                event.preventDefault();
                archivePopup.classList.remove("fade-out");
                archivePopup.classList.add("fade-in");
                archivePopup.classList.remove("hidden");
            });
        });
    
        // Close popup
        document.querySelector(".cd-popup").addEventListener("click", function (event) {
            if (
                event.target.matches(".cd-popup-close") ||
                event.target.matches(".no")
            ) {
                event.preventDefault();
                archivePopup.classList.add("fade-out");
                archivePopup.addEventListener("animationend", function(event) {
                    if (event.animationName === "fadeOut") {
                        archivePopup.classList.add("hidden");
                    }
                }, false);
                archivePopup.classList.remove("fade-in");
            }
        });
    }
   
    function discountPopup() {
        const discountPopup = document.querySelector(".discount-popup");
        document.querySelectorAll(".discount-popup-trigger").forEach(function (trigger) {
            trigger.addEventListener("click", function (event) {
                event.preventDefault();
                

                discountPopup.classList.remove("fade-out");
                discountPopup.classList.add("fade-in");
                discountPopup.classList.remove("hidden");
            });
        });
    
        // Close popup
        document.querySelector(".discount-popup").addEventListener("click", function (event) {
            if (
                event.target.matches(".discount-popup-close") ||
                event.target.matches(".discountCancel")
            ) {
                event.preventDefault();
                discountPopup.classList.add("fade-out");
                discountPopup.addEventListener("animationend", function(event) {
                    if (event.animationName === "fadeOut") {
                        discountPopup.classList.add("hidden");
                    }
                }, false);
                discountPopup.classList.remove("fade-in");
            }
        });
    }
    
    const savePopupNotif = document.querySelector(".save-popup");
    if (savePopupNotif) {
        function savePopup() {
            /* save pop up fade out*/
            document.querySelector(".save-popup").addEventListener("click", function (event) {
                if (event.target.matches(".save-popup-close")) {
                    event.preventDefault();
                    const popup = this;
                    popup.style.animation = "fadeOut 0.5s forwards"; 
    
                    // After animation ends, hide the element
                    popup.addEventListener("animationend", function() {
                        popup.style.display = "none";
                    });
                }
            });
        }
    }
   

    function salesChannelsPopup() {
        const salesPopup = document.querySelector(".sales-popup");
        document.querySelectorAll(".sales-channel-popup-trigger").forEach(function (trigger) {
            trigger.addEventListener("click", function (event) {
                event.preventDefault();
                salesPopup.classList.remove("fade-out");
                salesPopup.classList.add("fade-in");
                salesPopup.classList.remove("hidden");
            });
        });
    
        // Close popup
        document.querySelector(".sales-popup").addEventListener("click", function (event) {
            if (
                event.target.matches(".sales-popup-close") ||
                event.target.matches(".no")
            ) {
                event.preventDefault();
                salesPopup.classList.add("fade-out");
                salesPopup.addEventListener("animationend", function(event) {
                    if (event.animationName === "fadeOut") {
                        salesPopup.classList.add("hidden");
                    }
                }, false);
                salesPopup.classList.remove("fade-in");
            }
        });
    }

    archivePopUp();
    discountPopup();
    salesChannelsPopup();
});
