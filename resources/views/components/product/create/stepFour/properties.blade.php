<div>
    <h3>set eigenschappen</h3>
    @foreach ($properties as $property)
        @if ($property->type == 'text')
            <div>
                <label for="property_{{ $property->id }}">{{ $property->name }}</label>
                <input type="text" id="property_{{ $property->id }}" name="properties[{{ $property->id }}]">
            </div>
        @endif
    @endforeach
</div>