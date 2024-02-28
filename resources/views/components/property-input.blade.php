@props(['property'])
<div>
    @switch($property->type)
        @case('')
            
            @break
    
        @default
            <h1>error</h1>
    @endswitch
</div>