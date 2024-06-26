<!-- sets url path for img -->
<?php
    $app_url = env('VITE_APP_URL');
?>

<div class="flex justify-start items-center">
    <div class="flex justify-center items-center">
        <p class="w-[20rem] text-[#717171] text-[20px] font-bold">
			<!-- When component called, either Simple or Variable is set -->
            Product aanmaken | {{ $slot }}
        </p>
    </div>
    
    <div class="flex items-center gap-[1rem] justify-center basic:w-[33rem] hd:w-[54rem] uhd:w-[95rem]">
        <div class="prevBtn hover:cursor-pointer prevBtn">
            <img class="select-none" src="{{$app_url}}/images/arrow-left-icon.png" alt="arrow left">
        </div>
        <div class="h-[3.12rem] w-[14.06rem] text-[#717171] text-[20px] font-bold rounded-md flex justify-center items-center" style="border: 1px solid #717172;" id="currentStep">Stap 1 van {totalSteps}</div>
        <div id="headerNextBtnId" class="nextBtn hover:cursor-pointer nextBtn">
            <img class="select-none" src="{{$app_url}}/images/arrow-right-icon.png" alt="arrow right">
        </div>
    
    </div>
</div>

<x-product.create.progress-bar />
