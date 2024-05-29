<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <h1>sales Channels</h1>
    <form action="saleschannels/bulkdelete" method="POST">
        @csrf
        <ul>
            @foreach ($salesChannels as $salesChannel)
                <li>
                    <p>
                        <input name="saleschannels[]" type="checkbox" value="{{$salesChannel->id}}">
                        <a href="/saleschannels/{{$salesChannel->id}}">
                            {{ $salesChannel->id }}
                            {{ $salesChannel->name }}
                            {{ $salesChannel->channel_type }}
                        </a>
                    </p>

                </li>
            @endforeach
            <li>
                <input type="submit" value="bulk delete">
            </li>
        </ul>
    </form>
</x-layout._layout>