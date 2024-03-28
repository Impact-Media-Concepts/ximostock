document.addEventListener("DOMContentLoaded", function() {
    let sideNav = document.querySelector(".side-nav");
    let activeItem = document.querySelectorAll(".side-nav-active-item");
    let arrowCollapse = document.querySelector(".open-button");
    let textToHide = document.querySelectorAll(".text");
    
    let isHidden = false;

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