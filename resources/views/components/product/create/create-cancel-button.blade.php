<button @click="open = !open;"
    class="hover:bg-gray-100 duration-100 flex items-center z-20 w-[7.87rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100"
    style="border: 1px solid #717171" type="button" @click.away="open = false">
    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="gray"
            class=" cd-popup-close select-none flex items-center justify-center w-5 h-5"
        >
            <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6 18 18 6M6 6l12 12"
            class="cd-popup-close"
            />
        </svg>
    </div>
    <span class="w-52 text-left text-[14px] text-gray-700 line-clamp-1 relative right-2">
        <p>
            Annuleren
        </p>
    </span>
</button>