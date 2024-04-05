@props(['salesChannel' => []])

<script>
    let salesChannelsData = [
        @foreach ( $salesChannel as $salesChannel)
            {
                id:{{$salesChannel->id}},
                name: "{{$salesChannel->name}}"
            },
        @endforeach
    ];
</script>
