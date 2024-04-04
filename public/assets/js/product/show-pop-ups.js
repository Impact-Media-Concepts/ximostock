document.addEventListener("DOMContentLoaded", function () {
  // Open popup
  document.querySelectorAll(".cd-popup-trigger").forEach(function (trigger) {
      trigger.addEventListener("click", function (event) {
          event.preventDefault();
          document.querySelector(".cd-popup").classList.remove("hidden");
      });
  });

  // Close popup
  document.querySelector(".cd-popup").addEventListener("click", function (event) {
      if (
          event.target.matches(".cd-popup-close") ||
          event.target.matches(".no")
      ) {
          event.preventDefault();
          this.classList.add("hidden");
      }
  });
  
  // Open discount popup
  document.querySelectorAll(".discount-popup-trigger").forEach(function (trigger) {
      trigger.addEventListener("click", function (event) {
          event.preventDefault();
          document.querySelector(".discount-popup").classList.remove("hidden");
      });
  });

  // Close discount popup
  document.querySelector(".discount-popup").addEventListener("click", function (event) {
      if (
          event.target.matches(".discount-popup-close") ||
          event.target.matches(".discountCancel") ||
          event.target.matches(".discountSave")
      ) {
          event.preventDefault();
          this.classList.add("hidden");
      }
  });
});
