document.addEventListener("DOMContentLoaded", () => {
    const searchBar = document.getElementById('searchBar');
    searchBar.addEventListener('input', () => {
        const productSearchInput = document.getElementById('productSearchInput');
        productSearchInput.value = searchBar.value.trim();
    });
    searchBar.addEventListener('keydown', function(event) {
        if(event.key === 'Enter' ){
            const searchForm = document.getElementById('searchForm');
            searchForm.submit();
        }
    });

    orderByEvent("Name");
    orderByEvent("SKU");
    orderByEvent("UpdatedAt");
    orderByEvent("Stock");
    orderByEvent("Price");
    orderByEvent("Sold");
    orderByEvent("Status");
    
});

function orderByEvent(name){
    const OrderByName = document.getElementById(`orderBy${name}`);
    OrderByName.addEventListener('click', function(event){
        const orderByInput = document.getElementById('orderByInput');
        if(orderByInput.value == `${name}Descending`){
            orderByInput.value = `${name}Ascending`;
        }else{
            orderByInput.value = `${name}Descending`;
        }
        const searchForm = document.getElementById('searchForm');
        searchForm.submit();
    });
}
