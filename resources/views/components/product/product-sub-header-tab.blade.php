@props(['text', 'orderId', 'orderBy'])

<button id="orderBy{{$orderId}}" class="flex justify-center items-center {{ $attributes->get('container-class', '') }} {{ $text === 'Voorraad' ? 'w-6' : '' }}  cursor-pointer">
    <p class="text-[14px] text-left mt-[0.12rem]">
        {{ $text }}
    </p>
    
    <?php
        if ($orderBy === NULL && $orderId === 'UpdatedAt') {
            echo '<style>.rotate-arrow { transform: rotate(0deg); }</style>';
            echo '<img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem] rotate-arrow" src="../images/arrow-down-icon.png">';
        } elseif (str_contains($orderBy, $orderId)) {
            if (str_contains($orderBy, 'Descending')) {
                echo '<style>.rotate-arrow { transform: rotate(180deg); }</style>';
            } elseif (str_contains($orderBy, 'Ascending')) {
                echo '<style>.rotate-arrow { transform: rotate(0deg); }</style>';
            }
            echo '<img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem] rotate-arrow" src="../images/arrow-down-icon.png">';
        }
    ?>
</button>