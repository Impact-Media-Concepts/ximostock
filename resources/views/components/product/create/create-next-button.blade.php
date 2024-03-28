<button @click="open = !open;"
    class="hover:bg-[#3999BE] duration-100 flex items-center z-20 w-[10.53rem] px-[1.08rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
    style="border: 1px solid white" @click.away="open = false">
    <div class="flex mt-[0.08rem]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="white" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

    </div>
    <span class="pl-[0.2rem] text-left text-[14px] text-white">Nieuw toevoegen</span>
</button>