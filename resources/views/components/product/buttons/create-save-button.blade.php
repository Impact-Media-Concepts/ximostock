<?php
    $app_url = env('VITE_APP_URL');
?>

<button
    class="hover:bg-[#3999BE] duration-100 flex items-center justify-center z-20 w-[10.53rem] px-[1.08rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative right-[0.6rem] top-[0.02rem]"
    style="border: 1px solid white"
    id="saveBtn"
    type="submit">
    <div class="flex items-center justify-center">
        <div class="flex mt-[0.08rem]">
            <img class="select-none flex" src="{{$app_url}}/images/edit-icon.png" alt="edit icon">
        </div>
        <span class="pl-[0.5rem] text-left text-[14px] text-white">Aanmaken</span>
    </div>
</button>
  