<div class="bg-white rounded-t-lg hd:h-[50rem] uhd:h-[57rem] create-container-border">
    <h3>voorraden</h3>
    <label for="{{ $locations[0]->location_zones[0]->id }}">
        {{ $locations[0]->location_zones[0]->name }}
    </label>
    <input id="{{ $locations[0]->location_zones[0]->id }}" type="number" name="location_zones[{{ $locations[0]->location_zones[0]->id }}]">
    <label for="{{ $locations[0]->location_zones[1]->id }}">
        {{ $locations[0]->location_zones[1]->name }}
    </label>
    <input id="{{ $locations[0]->location_zones[1]->id }}" type="number" name="location_zones[{{ $locations[0]->location_zones[1]->id }}]">
</div>