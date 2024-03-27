function addListners(){
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
    })
}

addListners();

