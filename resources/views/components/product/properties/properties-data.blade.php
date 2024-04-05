@props(['properties' => [], 'selectedProperties' => []])

<?php
$propertyIds = $selectedProperties ? array_keys($selectedProperties) : [];
?>
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
            ],
            selected: {{ in_array($property->id, $propertyIds) ? "true" : "false" }},
            selectedOption:

            @if (in_array($property->id, $propertyIds) && isset($selectedProperties[$property->id]) &&  $selectedProperties[$property->id] != null)
                "{{$selectedProperties[$property->id]}}"
            @else
                null
            @endif
        },
    @endforeach
];
</script>