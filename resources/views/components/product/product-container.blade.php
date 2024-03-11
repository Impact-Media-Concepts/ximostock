@props(['products', 'perPage'])

<style>
    .bulk-actions-hidden {
        display: none;
    }
</style>

<div class="my-[7rem] mx-[2rem]">
    <x-product.product-header />
    <x-product.product-sub-header :products="$products" />
    <x-product.product-bulk-actions :products="$products" :perPage="$perPage"/>
    <x-product.product :products="$products" />
    <x-product.product-footer-pagination :perPage="$perPage" :products="$products" />
</div>

<script>
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

    let bulkActionsCheckboxSubheader = document.getElementById('bulkActionsCheckboxSubheader');
    let selectedCountElementBulk = document.getElementById('selectedCount');

    bulkActionsCheckboxSubheader.addEventListener('click', function() {

        let productItemCheckboxes = document.querySelectorAll('[id^="checkboxProductItem"]');
        let totalChecked = 0;

        productItemCheckboxes.forEach(function(productItemCheckbox) {
            let productId = productItemCheckbox.id.replace('checkboxProductItem', '');
            console.log(productId);
            productItemCheckbox.checked = bulkActionsCheckboxSubheader.checked;

            if (bulkActionsCheckboxSubheader.checked) {
                totalChecked++; // Increment total checked count if the header checkbox is checked
            }

            selectedCountElementBulk.textContent = totalChecked;
        });
    });
</script>
