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
    
    //unlink sales channels
    const unlinkSalesChannelsButton = document.getElementById('unlinkSalesChannels');
    unlinkSalesChannelsButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulkunlinksaleschannel';
    });
    
    //link sales channels
    const linkSalesChannelsButton = document.getElementById('linkSalesChannels');
    linkSalesChannelsButton.addEventListener('click', function(){
        BulkForm.action = '/products/bulklinksaleschannel';
    });
    
    //link sales channels
    const communicateStock = document.getElementById('bulkActioncommunicateStock');
    communicateStock.addEventListener('click', function(){
        BulkForm.action = '/products/bulkenablecommunicateStock';
    });
    
    //link sales channels
    const unCommunicateStock = document.getElementById('bulkActionunCommunicateStock');
    unCommunicateStock.addEventListener('click', function(){
        BulkForm.action = '/products/bulkdisablecommunicateStock';
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
