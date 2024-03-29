<a class="sideItem" href="{{ $href }}"> 
    <button
        {{ $attributes->merge(['class' => 'transition-all duration-200 flex w-[17.45rem] relative left-[0.1rem] h-[3.45rem] flex justify-center items-center hover:bg-[#F8F8F8] transition duration-100 delay-150' . (isset($active) && $active ? ' bg-[#F8F8F8]' : '')]) }}
        x-bind:class="isOpen ? 'w-[17.45rem]' : 'w-[4.08rem]'">
        <div class="flex w-[14.4rem] h-[3.43rem] items-center col gap-5 text-[16px]">
            @if (isset($icon))
                <img src="{{ $icon }}" alt="{{ $icon }} Icon" class="flex relative top-[0.13rem]">
            @endif
            <p class="flex relative top-[0.01rem]">
                {{ $slot }}
            </p>
        </div>
    </button>
</a>
