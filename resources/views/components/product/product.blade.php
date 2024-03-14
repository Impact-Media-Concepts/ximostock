@props(['products'])

<style>
    /* scrollbar */
    .product-scrollbar::-webkit-scrollbar {
        width: 0px;
    }

    ::-webkit-scrollbar {
        width: 20px;
    }

    ::-webkit-scrollbar-track {
        background: #fff;
        border: 1px solid #DBDBDB;
        border-radius: 15px;
        z-index: -1 !important;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #DBDBDB;
        border: 6px solid transparent;
        background-clip: content-box;
        border-radius: 15px;

        z-index: -1 !important;
    }

    ::-webkit-scrollbar-button:single-button {
        display: block;
        border-style: solid;
        height: 13px;
    }

    /* Up */
    ::-webkit-scrollbar-button:single-button:vertical:decrement {
        border-width: 10px 10px 10px 10px;
        border-color: transparent transparent #000000 transparent;
    }

    ::-webkit-scrollbar-button:single-button:vertical:decrement:hover {
        border-color: transparent transparent #524d4d transparent;
    }

    /* Down */
    ::-webkit-scrollbar-button:single-button:vertical:increment {
        border-width: 10px 10px 10px 10px;
        border-color: #000000 transparent transparent transparent;
    }

    ::-webkit-scrollbar-button:vertical:single-button:increment:hover {
        border-color: #524d4d transparent transparent transparent;
    }
</style>

<div class="relative flex items-center bg-white">
    <form class="relative" action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf
        <ul class="w-[78.81rem] overflow-y-auto overflow-x-hidden max-h-[43.75rem] product-scrollbar" id="container">
            @foreach ($products as $product)
                <x-product.product-item :product="$product" />
            @endforeach
        </ul>
        <button class="flex" type="submit">Delete Selected Products</button>
    </form>
</div>
<script>
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
</script>
