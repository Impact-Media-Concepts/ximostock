@props(['bulkActionId' => null])

<button id="{{$bulkActionId}}" {{ $attributes->merge(['class' => 'hover:bg-[#EAEAEA] duration-100 rounded-md text-[14px] flex justify-center items-center gap-[0.6rem]']) }}
    style="height: 34px; border: 1px solid #717171; font-weight: 400">
    {{ $slot }}
</button>

<script>
    // set button type based on pop up
    let bulkBtns = document.querySelectorAll({{$bulkActionId}});
    bulkBtns.forEach(bulkBtn => {
        const classes = bulkBtn.className.split(' ');
        if (classes.includes('cd-popup-trigger')) {
            bulkBtn.type = 'button';
        } else if (classes.includes('discount-popup-trigger')) {
            bulkBtn.type = 'button';
        } else if (classes.includes('sales-channel-popup-trigger')) {
            bulkBtn.type = 'button';
        } else if (classes.includes('communicate-stock')) {
            bulkBtn.type = '';
        } else if (classes.includes('uncommunicate-stock')) {
            bulkBtn.type = '';
        }
    });
</script>
