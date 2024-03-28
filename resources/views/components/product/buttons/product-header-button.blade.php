<button {{ $attributes->merge(['class' => 'hover:bg-[#3999BE] duration-100 rounded-md text-[14px] flex justify-center items-center gap-[0.6rem]']) }}
    style="height: 43px; border: 1px solid #fff; font-weight: 300">
    @if (isset($icon))
        <img src="{{ $icon }}" alt="Icon" class="select-none h-5 w-5">
    @endif
    {{ $slot }}
</button>
