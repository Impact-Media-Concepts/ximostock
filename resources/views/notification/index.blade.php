<ul>
    @foreach ($toProcessSales as $sale)
        <li>
            @dump($sale)
        </li>
    @endforeach
    @foreach ($errorSales as $sale)
    <li>
        @dump($sale)
    </li>
    @endforeach

</ul>
