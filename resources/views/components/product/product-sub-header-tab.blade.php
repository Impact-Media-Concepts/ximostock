@props(['orderBy', 'text', 'product'])

<button onclick="orderBy('{{ $text }}')" class="flex justify-center items-center {{ $attributes->get('container-class', '') }} cursor-pointer">
    <p class="text-[14px] mt-[0.12rem]">
        {{ $text }}
    </p>
    <img class="ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
</button>


<script>
    function orderBy(type) {
        switch (type) {
            case 'Naam':
                break;
            case 'SKU':
            console.log("yoo12");
                break;
            case 'Prijs':
                break;
            case 'Voorraad':
                break;
            case 'Verkocht':
                break;
            case 'Status':
                break;
            case 'Gewijzigd':
                break;

            default:
                break;
        }
    }
</script>