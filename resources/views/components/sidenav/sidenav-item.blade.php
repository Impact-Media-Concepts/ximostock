{{-- <div
    class="flex w-[17.12rem] h-[3.43rem] flex justify-center items-center hover:bg-[#F8F8F8] transition duration-100 delay-150">
    <div class="flex w-[14.12rem] h-[3.43rem] items-center col gap-3">
        <img class=" flex" src="../images/dashboard-icon.png" alt="dashboard icon">
        <p>
            Lorem, ipsum.
        </p>
    </div>
</div> --}}


<button
    {{ $attributes->merge(['class' => 'flex w-[17.12rem] h-[3.43rem] flex justify-center items-center hover:bg-[#F8F8F8] transition duration-100 delay-150']) }}>

    <div class="flex w-[14.12rem] h-[3.43rem] items-center col gap-3">
        @if (isset($icon))
            <img src="{{ $icon }}" alt="{{ $icon }} Icon" class="flex">
        @endif
        <p>
            {{ $slot }}
        </p>
    </div>

</button>
