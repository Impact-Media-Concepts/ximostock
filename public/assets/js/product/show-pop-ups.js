//#region pop up
jQuery(document).ready(function ($) {
  // Open popup
    $(".cd-popup-trigger").on("click", function (event) {
    event.preventDefault();
    $(".cd-popup").removeClass("hidden");
    }); 

  // Close popup
    $(".cd-popup").on("click", function (event) {
    if (
        $(event.target).is(".cd-popup-close") ||
        $(event.target).is(".no")
    ) {
        event.preventDefault();
        $(this).addClass("hidden");
    }
    });
});
//#endregion

jQuery(document).ready(function ($) {
  // Open popup
    $(".discount-popup-trigger").on("click", function (event) {
    event.preventDefault();
    $(".discount-popup").removeClass("hidden");
    }); 

  // Close popup
    $(".discount-popup").on("click", function (event) {
    if (
        $(event.target).is(".discount-popup-close") ||
        $(event.target).is(".discountCancel") ||
        $(event.target).is(".discountSave")
    ) {
        event.preventDefault();
        $(this).addClass("hidden");
    }
    });
});
