let sideNavOverlay = document.getElementById("sideNavOverlay");

sideNavOverlay.classList.add('hidden');

function toggleWidth() {
    let sideNavItemText = document.querySelectorAll('[id^="text_"]');
    let sidenavContainer = document.getElementById("sidenavContainer");
    
    let sideNavItemA = document.querySelectorAll('[id^="sideNavItemA_"]');
    
    let containsLarge = false;
    
    sidenavContainer.classList.toggle("large");
    
    function toggleSideNavItem() {
        sideNavItemA.forEach(element => {
            element.classList.toggle("large");
            let isLargeItem = element.classList.contains("large");
            
            if (isLargeItem) {
                sideNavOverlay.classList.remove('hidden');
                containsLarge = true;
            } else {
                sideNavOverlay.classList.add('hidden');
                containsLarge = false;
            }
            
            document.cookie = "sideNavItemA_width=" + (isLargeItem ? "" : "large") + "; path=/";
        });
    }
    
    function toggleSideNavText() {
        sideNavItemText.forEach(element => {
            if (containsLarge === true) {
                
                setTimeout(() => {
                    element.classList.remove("hidden");
                }, 170);
            } else if (containsLarge === false) {
                
                setTimeout(() => {
                    element.classList.add("hidden");
                }, 100);
                
            }
            document.cookie = "sideNavItemText_hidden=" + (containsLarge ? "" : "hidden") + "; path=/";
        });
    }
    
    function toggleSideNavContainerIcon() {
        let arrowCollapse = document.getElementById("openSideNavButton");
        let arrowIcon = document.getElementById("arrowIcon");
        if (containsLarge === true) {
            arrowIcon.classList.remove("rotate-arrows222");
            arrowCollapse.classList.remove("rotate-arrows");
        } else if (containsLarge === false) {
            arrowIcon.classList.remove("rotate-arrows222");
            arrowCollapse.classList.add("rotate-arrows");
        }
        let isRotated = arrowCollapse.classList.contains("rotate-arrows");
        document.cookie = "openButton_rotate=" + (isRotated ? "" : "arrows") + "; path=/";
    }
    
    function toggleContainer() {
        let isLarge = sidenavContainer.classList.contains("large");
        document.cookie = "sidenavContainer_width=" + (isLarge ? "" : "large") + "; path=/";
    }
    
    toggleSideNavItem();
    toggleSideNavText();
    toggleSideNavContainerIcon();
    toggleContainer();
}
