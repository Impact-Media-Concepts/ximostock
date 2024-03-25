// introduce variables
let bulkActionsCheckboxSubheader = document.getElementById('bulkActionsCheckboxSubheader');
let selectedCountElementBulk = document.getElementById('selectedCount');
// listens if checkbox inside product-sub-header to changed if so, then hide or show product-bulk-actions component
document.addEventListener('DOMContentLoaded', function() {
    const bulkActionsCheckboxSubheader = document.getElementById('bulkActionsCheckboxSubheader');
    const productBulkActionsContainer = document.getElementById('productBulkActionsContainer');
    bulkActionsCheckboxSubheader.addEventListener('change', function() {
        if (this.checked) {
            productBulkActionsContainer.classList.remove('bulk-actions-hidden');
        } else {
            productBulkActionsContainer.classList.add('bulk-actions-hidden');
        }
    });
});

// listens if checkbox inside product-sub-header to changed
// it takes the cound of totalchecked and increases i
// when a checkbox is checked
bulkActionsCheckboxSubheader.addEventListener('change', function() {
    // gets all items with thhat classname and after it has id.
    let productItemCheckboxes = document.querySelectorAll('[id^="checkboxProductItem"]');
    let totalChecked = 0;
    // for each productItemCheckbox it checks it or uncheckes it
    productItemCheckboxes.forEach(function(productItemCheckbox) {
        productItemCheckbox.checked = bulkActionsCheckboxSubheader.checked;
        if (bulkActionsCheckboxSubheader.checked) {
            // increases the total amount of checked or unchecked
            totalChecked++;
        }
        // sets the total checked as the textContent to display for user
        selectedCountElementBulk.textContent = totalChecked;
    });
});

let bulkActionsSelectAll = document.getElementById('selectAll');
let selectedCountElementBulkSelectAll = document.getElementById('selectedCount');

bulkActionsSelectAll.addEventListener('click', function() {
    let productItemCheckboxesSelectAll = document.querySelectorAll('[id^="checkboxProductItem"]');
    let totalCheckedSelected = 0;
    productItemCheckboxesSelectAll.forEach(function(productItemCheckbox) {
         // If the checkbox is not already checked, check it and increment totalCheckedSelected
        if (!productItemCheckbox.checked) {
            productItemCheckbox.checked = true;
            totalCheckedSelected++;
        }
    });
    // Update totalChecked by adding totalCheckedSelected to the current count
    let totalChecked = parseInt(selectedCountElementBulk.textContent) + totalCheckedSelected;
    // Update the displayed count of selected items
    selectedCountElementBulk.textContent = totalChecked;
});
