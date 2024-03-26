let sideBar = document.querySelector(".side-bar");
let activeItem = document.querySelectorAll(".side-bar-active-item");
let arrowCollapse = document.querySelector(".open-button");
let textToHide = document.querySelectorAll(".text");

let isHidden = false;

// Function to toggle sidenav state
function toggleSidenav() {
    arrowCollapse.classList.toggle("rotate-arrows");
    sideBar.classList.toggle("close-sidenav");

    const isCollapsed = sideBar.classList.contains("close-sidenav");
    activeItem.forEach(element => {
        element.style.width = isCollapsed ? "4.08rem" : "17.06rem";
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

    // Store sidenav state in localStorage
    localStorage.setItem('sidenavCollapsed', isCollapsed);
}

// Check if sidenav state is stored in localStorage
const storedCollapsed = localStorage.getItem('sidenavCollapsed');
if (storedCollapsed === 'true') {
    toggleSidenav();
}

// Event listener for sidenav toggle
arrowCollapse.onclick = toggleSidenav;