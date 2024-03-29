<div
    class="cd-popup w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
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
        class="cd-popup-container relative w-[37.25rem] h-[26.18rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >
        <div
            class="cd-popup-close flex justify-end items-center relative bottom-4 left-2"
        >
            <a
                href="#0"
                class="cd-popup-close w-10 h-10 flex items-center justify-center cursor-pointer"
            >
                <svg
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
                <img class="w-[5.56rem] h-[4.75]" src="../images/archive-warning-icon.png"> 
            </div>
                <h2
                    class="py-4 text-lg md:text-2xl font-bold text-gray-500 text-gray-500"
                    x-text="message"
                >
                </h2>
                <p class="pb-4 md:pb-10 text-gray-500">
                    <span x-text="explanation_part1"></span>
                    <a
                        href="/archive"
                        class="text-blue-500 underline cursor-pointer"
                        x-text="explanation_archive"
                    >
                    </a>
                    <span x-text="explanation_part2"></span>
                </p>
            <ul
                class="cd-buttons flex-grid justify-center items-center md:pb-2 md:flex"
            >
                
                <li class="py-1  md:mr-8 md:py-0">
                    <button
                    x-transition
                    class="no w-[13.43rem] border-gray-400 border-2 py-2 rounded-lg text-gray-500 flex justify-center items-center hover:bg-gray-100 duration-100"
                    href="#0"
                    x-text="no"
                    type="button"
                    ></button>
                </li>
                <li class="py-1 md:py-0 h">
                    <button
                    class="yes w-[13.43rem] text-white bg-[#FD9827] duration-100 py-2.5 rounded-lg flex justify-center items-center"
                    href="#0"
                    x-text="yes"
                    type="submit"
                    name="archiveButton"
                    id="archiveButton"
                    value="archiveButton"
                    ></button>
                </li>
            </ul>
        </div>
    </div>
</div>