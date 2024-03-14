<!-- <div class="flex justify-center items-center">
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
    </div> -->