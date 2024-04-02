document.addEventListener("DOMContentLoaded", () => {
    const BulkForm = document.getElementById('bulkActionsForm');
    //delete
    const DeleteFormButton = document.getElementById('bulkActionArchive');
    DeleteFormButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulkdelete';
    });

    //discount
    const DiscountFormButton = document.getElementById('bulkActionDiscount');
    DiscountFormButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulkdiscount';
    });
    const round = document.getElementById('roundDiscount');
    const trueRound = document.getElementById('trueRoundDiscount');
    round.addEventListener('click', function(){
        if(round.checked){
            trueRound.value = 1;
        }else{
            trueRound.value = 0;
        }
    });

});