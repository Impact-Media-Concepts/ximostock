
let sideBar = document.querySelector(".side-bar");
let activeItem = document.querySelectorAll(".side-bar-active-item");
let arrowCollapse = document.querySelector(".open-button");
let textToHide = document.querySelectorAll(".text");

let isHidden = false;

$(document).ready(function() {
  // each element which id starts with checkboxProductItem
  $('[id^="sideItem"]').each(function() {
      let sideItem = document.querySelectorAll('[id^="sideItem"]');
      let closeButton = document.querySelectorAll('closeButton');

      let sideid = $(productItemCheckbox).data('sideItem-id');

      // productItemCheckbox is clicked execute next code
      $(closeButton).on('click', function() {
          sideItem.classList.add("w-[4.08rem]")
      });
  });
});

arrowCollapse.onclick = () => {
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
};
