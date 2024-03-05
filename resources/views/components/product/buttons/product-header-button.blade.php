<button {{ $attributes->merge(['class' => 'rounded-md text-[14px] flex justify-center items-center gap-[0.6rem]']) }}
    style="height: 43px; border: 1px solid #fff; font-weight: 300">
    @if (isset($icon))
        <img src="{{ $icon }}" alt="Icon" class="h-5 w-5">
    @endif
    {{ $slot }}
</button>
