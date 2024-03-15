<button {{ $attributes->merge(['class' => 'rounded-md text-[14px] flex justify-center items-center gap-[0.6rem]']) }}
    style="height: 34px; border: 1px solid #717171; font-weight: 400">
    {{ $slot }}
</button>
