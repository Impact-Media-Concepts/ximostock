@props(['properties' => []])

<script>
    let propertiesData = [
    @foreach ( $properties as $property)
        {
            id:{{$property->id}},
            name: "{{$property->name}}",
            type:"{{$property->type}}",
            options:[
                @foreach ($property->options as $option)
                    "{{$option}}",
                @endforeach
            ]
        },
    @endforeach
];
</script>