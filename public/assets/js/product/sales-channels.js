document.addEventListener("DOMContentLoaded", (event) => {
  //slider
  let slideon = new Slideon();
  slideon.load();

  // check and uncheck all
  const selectAllCheckbox = document.getElementById("selectAllSalesChannels");

  selectAllCheckbox.addEventListener("change", function() {

      // every sales-channels-item.blade with that id
      const selectSalesItems = document.querySelectorAll("#selectSalesItem");

      selectSalesItems.forEach(function(checkbox) {

          checkbox.checked = selectAllCheckbox.checked;
      });
  });
});