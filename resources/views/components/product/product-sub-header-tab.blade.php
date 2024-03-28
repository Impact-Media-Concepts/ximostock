@props(['text', 'orderId'])

<button id="orderBy{{$orderId}}" class="flex justify-center items-center {{ $attributes->get('container-class', '') }} cursor-pointer">
    <p class="{{ $text === 'Voorraad' ? 'w-6' : '' }} text-[14px] text-left mt-[0.12rem]">
        {{ $text }}
    </p>
    <img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
</button>