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

    let productItemCheckboxes = document.querySelectorAll('[id^="checkboxProductItem"]');
    let totalChecked = 0;

    productItemCheckboxes.forEach(function(productItemCheckbox) {
        let productId = productItemCheckbox.id.replace('checkboxProductItem', '');
        console.log(productId);
        productItemCheckbox.checked = bulkActionsCheckboxSubheader.checked;

        if (bulkActionsCheckboxSubheader.checked) {
            totalChecked++;
        }

        selectedCountElementBulk.textContent = totalChecked;
    });
});
 
let bulkActionsCheckboxSubheaderSelectAll = document.getElementById('bulkActionsCheckboxSubheader2');
let selectedCountElementBulkSelectAll = document.getElementById('selectedCount');

    // listens if checkbox inside product-sub-header to changed if so, then hide or show product-bulk-actions component
    document.addEventListener('DOMContentLoaded', function() {
     const bulkActionsCheckboxSubheaderSelectAll = document.getElementById('bulkActionsCheckboxSubheader');
     const productBulkActionsContainer = document.getElementById('productBulkActionsContainer');
     bulkActionsCheckboxSubheaderSelectAll.addEventListener('change', function() {
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
 if (bulkActionsCheckboxSubheaderSelectAll) {
        bulkActionsCheckboxSubheaderSelectAll.addEventListener('click', function() {

        let productItemCheckboxess = document.querySelectorAll('[id^="checkboxProductItem"]');
        let totalCheckeds = 0;

        productItemCheckboxess.forEach(function(productItemCheckbox) {
            let productId = productItemCheckbox.id.replace('checkboxProductItem', '');
            
            productItemCheckbox.checked = bulkActionsCheckboxSubheaderSelectAll.checked;

            if (bulkActionsCheckboxSubheaderSelectAll.checked) {
                totalCheckeds++;
            }

            selectedCountElementBulkSelectAll.textContent = totalCheckeds;
        });
     });
 }
