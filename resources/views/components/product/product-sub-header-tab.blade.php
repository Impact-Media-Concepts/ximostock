@props(['text', 'orderId', 'orderBy'])

<button id="orderBy{{$orderId}}" class="flex justify-center items-center {{ $attributes->get('container-class', '') }} {{ $text === 'Voorraad' ? 'w-6' : '' }}  cursor-pointer">
    <p class="text-[14px] text-left mt-[0.12rem]">
        {{ $text }}
    </p>
    
    <?php
        if ($orderBy === NULL && $orderId === 'UpdatedAt') {
            echo '<img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem] rotate-arrow-no-rotate" src="../images/arrow-down-icon.png" alt="arrow down icon">';
        } elseif (str_contains($orderBy, $orderId)) {
            $rotateClass = '';
            if (str_contains($orderBy, 'Descending')) {
                $rotateClass = 'rotate-arrow';
            } elseif (str_contains($orderBy, 'Ascending')) {
                $rotateClass = 'rotate-arrow-no-rotate';
            }
            echo '<img class="select-none ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem] ' . $rotateClass . '" src="../images/arrow-down-icon.png" alt="arrow down icon">';
        }
    ?>
</button>
