<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
<div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Storage Locations</h1>
        <form action="#" method="POST">
            @csrf
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Select
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Location Name
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Location Zones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <input type="checkbox" name="locations[]" value="{{ $location->id }}" class="form-checkbox h-5 w-5 text-indigo-600">
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <a href="{{ url('/locations/' . $location->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $location->name }}
                                    </a>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($location->location_zones->isNotEmpty())
                                        <ul class="list-disc list-inside">
                                            @foreach ($location->location_zones as $zone)
                                                <li>{{ $zone->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>No zones available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Apply Bulk Actions
                </button>
            </div>
        </form>
    </div>
</x-layout._layout>
