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
