<!-- sets url path for img -->
<?php
    $app_url = env('VITE_APP_URL');
?>

<div class="w-[15.93rem] bg-[#E30F40] flex items-center rounded-md error-popup px-[1rem]">
    <div class="w-full my-[0.8rem]">
        <p class="text-[#fff] text-[14px] flex justify-start items-center flex-wrap">
            Lorem ipsum dolor sit
        </p>
    </div>
    
    <img
        src="{{ $app_url }}/images/white-x-icon.png"
        alt="white x icon"
        class="error-popup-close hover:cursor-pointer h-[1rem] w-[1rem] select-none"
    >
</div>
