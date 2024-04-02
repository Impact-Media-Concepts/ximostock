document.addEventListener("DOMContentLoaded", () => {
    const BulkForm = document.getElementById('bulkActionsForm');

    const DeleteFormButton = document.getElementById('bulkActionArchive');
    DeleteFormButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulkdelete';
    });

    const DiscountFormButton = document.getElementById('bulkActionDiscount');
    DiscountFormButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulkdiscount';
    });
});