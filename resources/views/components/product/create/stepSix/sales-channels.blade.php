<?php
echo $salesChannels
?>

<div class="bg-white rounded-t-lg uhd:h-[59rem]" style="border: 1px solid #F0F0F0;">
    <div class="h-[4.56rem] hd:w-[95rem] uhd:w-[138rem] rounded-t-lg" style="border:  1px solid #F0F0F0;">
        <div class=" ml-[1.56rem] mt-[0.6rem]">
            <div>
                <p class="text-[18px] font-bold text-[#717171]">
                    Verkoopkanalen
                </p>
            </div>
            <div>
                <p class="text-[14px] text-[#717171]">
                    Hier kan je verkoopkanalen koppelen
                </p>
            </div>
        </div>
    </div>
    <ul>
        @foreach ($salesChannels as $channel)
            <li>
                <label for="salesChannel[{{ $channel->id }}]">{{ $channel->name }}</label>
                <input type="checkbox" name="salesChannels[]" value="{{ $channel->id }}"
                    id="salesChannel[{{ $channel->id }}]">
            </li>
        @endforeach
    </ul>
</div>
