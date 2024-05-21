@props(['bulkActionId' => null])

<!-- merge is used to get the class from parent component, and add them to this components classes -->
<button id="{{$bulkActionId}}" {{ $attributes->merge(['class' => 'hover:bg-[#EAEAEA] duration-100 rounded-md text-[14px] flex justify-center items-center gap-[0.6rem]']) }}
    style="height: 34px; border: 1px solid #717171; font-weight: 400">
    {{ $slot }}
</button>
