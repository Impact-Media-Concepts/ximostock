@php
    $headerButtons = [
        ['text' => 'Import', 'width' => '6.31rem', 'icon' => '../images/import-icon.png'],
        ['text' => 'Export', 'width' => '6.31rem', 'icon' => '../images/export-icon.png'],
    ];
@endphp

<div class="w-[78.85rem] h-[4.65rem] flex items-center bg-[#3DABD5] rounded-t-lg pt-1">
    <h1 class="relative left-[1.55rem] bottom-[0.2rem] text-white text-[18px]"
        style="font-family: 'Inter', sans-serif; font-weight:bold">
        <p>
            Main product page
        </p>
    </h1>
    <div class="flex justify-center left-[33.8rem] items-center gap-[0.7rem] pb-[0.15rem] text-white relative left-[53.2rem]"
        style="font-family: 'Inter', sans-serif;">
        @foreach ($headerButtons as $button)
            <x-product.buttons.product-header-button class="w-[{{ $button['width'] }}] {{ $button['text'] === 'Archiveren' ? 'cd-popup-trigger' : '' }}"  icon="{{ $button['icon'] }}">
                {{ $button['text'] }}
            </x-product.buttons.product-header-button>
        @endforeach
    </div>
    <div class="flex justify-center items-center">
        <div
            class="cd-popup w-full h-full fixed top-0 bg-black bg-opacity-60 hidden pt-32 select-none left-0 z-30"
        >
            <div
            x-data="{
            message: 'Weet u zeker dat u dit product wilt archiveren?',
            explanation_part1: 'Als u op \'ja\' klikt, zal het product naar het archief worden verplaatst. Op de',
            explanation_archive: 'archief',
            explanation_part2: 'pagina kunt u dit product terugvinden en naar keuze terugzetten.',
            yes: 'Ja ik weet het zeker!',
            no: 'Nee toch maar niet'
        }"
            x-transition
            class="cd-popup-container relative w-11/12 md:w-1/2 lg:w-1/3 bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
            >
            <div
                class="cd-popup-close flex justify-end items-center relative bottom-4 left-2"
            >
                <a
                href="#0"
                class="cd-popup-close w-10 h-10 flex items-center justify-center cursor-pointer"
                ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="gray"
                    class="cd-popup-close flex items-center justify-center w-8 h-8 cursor-pointer"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18 18 6M6 6l12 12"
                    />
                </svg>
                </a>
            </div>
            <div class="relative bottom-8">
                <div class="flex justify-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="red"
                    class="w-20 h-20"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"
                    />
                </svg>
                </div>
                <h2
                class="py-4 text-lg md:text-2xl font-bold text-gray-500 text-gray-500"
                x-text="message"
                ></h2>
                <p class="pb-4 md:pb-10 text-gray-500">
                <span x-text="explanation_part1"></span>
                <a
                    href="#"
                    class="text-blue-500 underline cursor-pointer"
                    x-text="explanation_archive"
                >
                </a>
                <span x-text="explanation_part2"></span>
                </p>
                <ul
                class="cd-buttons flex-grid justify-center items-center md:pb-2 md:flex"
                >
                <li class="py-1 md:mr-8 md:py-0">
                    <a
                    x-transition
                    class="no hover:text-white w-66 md:w-64 text-white border-gray-400 border-2 py-2 rounded-lg !text-gray-500 flex justify-center items-center hover:bg-gray-500 duration-200"
                    href="#0"
                    x-text="no"
                    ></a>
                </li>
                <li class="py-1 md:py-0 h">
                    <a
                    class="yes w-66 md:w-64 text-white bg-red-500 py-2.5 rounded-lg flex justify-center items-center"
                    href="#0"
                    x-text="yes"
                    ></a>
                </li>
                </ul>
            </div>
            </div>
        </div>
        </div>
    </div>
<script>
    //#region pop up
    jQuery(document).ready(function ($) {
      // Open popup
        $(".cd-popup-trigger").on("click", function (event) {
        event.preventDefault();
        $(".cd-popup").removeClass("hidden");
        }); 

      // Close popup
        $(".cd-popup").on("click", function (event) {
        if (
            $(event.target).is(".cd-popup-close") ||
            $(event.target).is(".yes") ||
            $(event.target).is(".no")
        ) {
            event.preventDefault();
            $(this).addClass("hidden");
        }
        });
    });
    //#endregion

    $.fn.dragCheck = function (selector) {
        if (selector === false)
        return this.find("*")
            .andSelf()
            .add(document)
            .unbind(".dc")
            .removeClass("dc-selected")
            .filter(":has(:checkbox)")
            .css({ MozUserSelect: "", cursor: "" });
            else
        return this.each(function () {
          // if a checkbox is clicked this will be set to
          // it's checked state (true||false), otherwise null
            let mdown = null;

          // get the specified container, or children if not specified
            $(this)
            .find(selector || "> *")
            .filter(":has(:checkbox)")
            .each(function () {
              // highlight all already checked boxes
                if ($(this).find(":checkbox:checked").length)
                $(this).addClass("dc-selected");
            })
            .bind("mouseover.dc", function () {
              // if a checkbox was clicked and mouse button bein held down
                if (mdown != null) {
                // set this container's checkbox to the
                // same state as the one first clicked
                $(this).find(":checkbox")[0].checked = mdown;
                // add the highlight class
                $(this).toggleClass("dc-selected", mdown);
                }
            })
            .bind("mousedown.dc", function (e) {
              // find this container's checkbox
                let t = e.target;
                if (!$(t).is(":checkbox")) t = $(this).find(":checkbox")[0];

              // switch it's state (click event will be canceled later)
                t.checked = !t.checked;
              // set the value to which other hovered
              // checkboxes will be set while the mouse is down
                mdown = t.checked;

              // highlight this one according to it's state
                $(this).toggleClass("dc-selected", mdown);
            })

            // avoid text selection
            .bind("selectstart.dc", function () {
                return false;
            })
            .css({
                MozUserSelect: "none",
                cursor: "default",
            })

            // cancel the click event on the checkboxes because
            // we already switched it's checked state on mousedown
            .find(":checkbox")
            .bind("click.dc", function () {
                return false;
            });

          // clear the mdown let if the mouse button is released
          // anywhere on the page
            $(document).bind("mouseup.dc", function () {
            mdown = null;
            });
        });
    };

    $("#container").dragCheck(".checkbox-row");
</script>
