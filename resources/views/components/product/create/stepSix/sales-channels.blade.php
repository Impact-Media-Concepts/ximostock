<div>
    <h3>sales channels</h3>
    <ul>
        @foreach ($salesChannels as $channel)
            <li>
                <label for="salesChannel[{{ $channel->id }}]">{{ $channel->name }}</label>
                <input type="checkbox" name="salesChannels[]" value="{{ $channel->id }}"
                    id="salesChannel[{{ $channel->id }}]">
            </li>
        @endforeach
    </ul>
</div>
<x-product.buttons.create-save-button />