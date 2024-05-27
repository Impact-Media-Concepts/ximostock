$(document).ready(function() {
    // each element which id starts with checkboxProductItem
    $('[id^="checkboxProductItem"]').each(function() {
        let productItemCheckbox = this;
        let productId = $(productItemCheckbox).data('product-id');
        
        // productItemCheckbox is clicked execute next code
        $(productItemCheckbox).on('click', function() {
            let productBulkActionsContainer = $('#productBulkActionsContainer');
            let selectedCountElementItem = $('#selectedCount');
            let totalCheckedCheckboxes = $('[id^="checkboxProductItem"]:checked').length;
            
            if ($(this).is(':checked')) {
                // When the checkbox is unchecked
                // if it checked
                // then totalCheckedCheckboxes + 1
                // and remove bulk-actions-hidden.
                selectedCountElementItem.text(totalCheckedCheckboxes);
                if (totalCheckedCheckboxes === 1) {
                    productBulkActionsContainer.removeClass('bulk-actions-hidden');
                }
            }
            // if clicked productItemCheckbox is unchecked
            // then totalCheckedCheckboxes is -1
            // and adds class bulk-actions-hidden to id #productBulkActionsContainer
            else {
                selectedCountElementItem.text(totalCheckedCheckboxes);
                if (totalCheckedCheckboxes === 0) {
                    productBulkActionsContainer.addClass('bulk-actions-hidden');
                }
            }
        });
    });
});
