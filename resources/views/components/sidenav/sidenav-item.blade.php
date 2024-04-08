<style>
    .transition-700 {
        transition: all 500ms ease;
    }
</style>

<a id="sidenav-item-a" class="side-nav-active-item transition-700 sideItem" href="{{ $href }}"> 
    <button
        id="sidenav-item-button"
        {{ $attributes->merge(['class' => 'transition-all duration-[400ms] w-full flex justify-start pl-5 items-center hover:bg-[#F8F8F8]' . (isset($active) && $active ? ' bg-[#F8F8F8]' : '')]) }}">
        <div class="w-full h-[3.45rem] flex items-center gap-5 text-[16px]">
            @if (isset($icon))
                <img src="{{ $icon }}" alt="{{ $slug }} icon" class="select-none relative top-[0.13rem]">
            @endif
            <p class="text flex relative top-[0.01rem]">
                {{ $slot }}
            </p>
        </div>
    </button>
</a>
