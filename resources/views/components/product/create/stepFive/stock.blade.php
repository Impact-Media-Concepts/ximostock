<div>
    <h3>voorraden</h3>
    <label for="{{ $locations[0]->location_zones[0]->id }}">
        {{ $locations[0]->location_zones[0]->name }}
    </label>
    <input type="number" name="location_zones[{{ $locations[0]->location_zones[0]->id }}]">
    <label for="{{ $locations[0]->location_zones[1]->id }}">
        {{ $locations[0]->location_zones[1]->name }}
    </label>
    <input type="number" name="location_zones[{{ $locations[0]->location_zones[1]->id }}]">
</div>