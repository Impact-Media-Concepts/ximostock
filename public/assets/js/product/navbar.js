document.addEventListener("DOMContentLoaded", function() {
    let sideNav = document.querySelector(".side-nav");
    let activeItem = document.querySelectorAll(".side-nav-active-item");
    let arrowCollapse = document.querySelector(".open-button");
    let basicBackground = document.querySelector(".basic-bg");
    let textToHide = document.querySelectorAll(".text");
    
    let isHidden = false;

    arrowCollapse.addEventListener("click", function (event) {
        if (!basicBackground.classList.contains("fade-in") && !basicBackground.classList.contains("fade-out")) {
            basicBackground.classList.add("fade-out");

            basicBackground.addEventListener("animationend", function (event) {
                if (event.animationName === "fadeOut") {
                    basicBackground.classList.add("hidden");
                }
            }, false);

        } else if (basicBackground.classList.contains("fade-in")) {
            basicBackground.classList.remove("fade-in");
            basicBackground.classList.add("fade-out");

            basicBackground.addEventListener("animationend", function (event) {
                if (event.animationName === "fadeOut") {
                    basicBackground.classList.add("hidden");
                }
            }, false);

        } else if (basicBackground.classList.contains("fade-out")) {
            basicBackground.classList.remove("fade-out");
            basicBackground.classList.remove("hidden");
            basicBackground.classList.add("fade-in");
        }
    });

    function toggleSidenav() {
        arrowCollapse.classList.toggle("rotate-arrows");
        sideNav.classList.toggle("close-sidenav");

        const isCollapsed = sideNav.classList.contains("close-sidenav");
        activeItem.forEach(element => {
            element.classList.toggle("close-sidenav", isCollapsed);
        });
    
        const toggleTextVisibility = () => {
            textToHide.forEach(element => {
                element.classList.toggle("hidden", isCollapsed);
            });
        };
    
        if (!isHidden) {
            toggleTextVisibility();
            isHidden = true;
        } else {
            setTimeout(() => {
                toggleTextVisibility();
                isHidden = false;
            }, 250);
        }
    }
    arrowCollapse.onclick = toggleSidenav;
});
