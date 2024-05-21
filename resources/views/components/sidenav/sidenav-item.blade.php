<style>
    .transition-700 {
        transition: all 500ms ease;
    }
</style>

<!-- Checks for a cookie -->
<a id="sideNavItemA_{{ $slug }}" class="<?php echo isset($_COOKIE['sideNavItemA_width']) && $_COOKIE['sideNavItemA_width'] === 'large' ? 'rectangle' : 'rectangle large'; ?> side-nav-active-item transition-700 sideItem" href="{{ $href }}"> 
    <button
        id="sidenav_item_button_{{ $slug }}"
        {{ $attributes->merge(['class' => 'sidenav-item-button transition-all duration-[400ms] w-full flex justify-start pl-5 items-center hover:bg-[#F8F8F8]' . (isset($active) && $active ? ' bg-[#F8F8F8]' : '')]) }}">
        <div class="w-full h-[3.45rem] flex items-center gap-5 text-[16px]">
            @if (isset($icon))
                <img src="{{ $icon }}" alt="{{ $slug }} icon" class="select-none relative top-[0.13rem]">
            @endif
            <p id="text_{{ $slug }}" class="<?php echo isset($_COOKIE['sideNavItemText_hidden']) && $_COOKIE['sideNavItemText_hidden'] === 'hidden' ? 'hidden' : 'inline'; ?> flex relative top-[0.01rem]">
                {{ $slot }}
            </p>
        </div>
    </button>
</a>
