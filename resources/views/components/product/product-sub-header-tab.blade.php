@props(['text', 'orderId'])

<button id="orderBy{{$orderId}}" class="flex justify-center items-center {{ $attributes->get('container-class', '') }} cursor-pointer">
    <p class="text-[14px] mt-[0.12rem]">
        {{ $text }}
    </p>
    <img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
</button>