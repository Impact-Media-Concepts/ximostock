@props(['salesChannels' => []])

<script>
    let salesChannelsData = [
        @foreach ( $salesChannels as $salesChannel)
            {
                id:{{$salesChannel->id}},
                name: "{{ $salesChannel->name }}"
            },
        @endforeach
    ];
</script>
