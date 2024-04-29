@props(['salesChannel'])

<div class="flex justify-center py-[0.5rem]">
    <div class="w-[63rem] h-[3.68rem] flex items-center bg-[#F8F8F8] rounded-md" style="border: 1px solid #F0F0F0;">
        <div class="px-[1.25rem]">
            <img class="w-[3.56rem] h-[2.56rem]" src="../images/save-icon.png">
        </div>

        <div class="w-full flex">
            <p>{{ $salesChannel->name }}</p>
        </div>

        <div>
            <div class="flex items-center justify-end mr-[1.5rem]">
                <input id="selectSalesItem" type="checkbox" class="slideon slideon-auto" name="sales_channel_id[]">
            </div>
        </div>
    </div>
</div>
