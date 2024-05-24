@props(['properties'])

<ul>
    @foreach ($properties as $property)
        @switch($property->type)
            @case('multiselect')
                    
                    @break
                @case('singleselect')
                    
                    @break
                @case('number')
                    
                    @break
                @case('text')
                    
                    @break
                @case('bool')
                    
                    @break
                @default
        @endswitch
    @endforeach
</ul>
