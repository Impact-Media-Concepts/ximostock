<style>
    input::placeholder {
        color: #D3D3D3;
        opacity: 1;
        font-weight: 400;
    }

    input::-ms-input-placeholder {
        color: #D3D3D3;
        font-weight: 400;
    }
</style>

<div class="w-full h-[5.05rem] absolute shadow-[0_1px_14px_-5px_rgba(0,0,0,0.3)] bg-white flex items-center">
    <div class="px-11 pt-1 relative left-[0.08rem]">
        <img class="w-[10.75] h-[1.31rem] flex" src="../images/ximostock-logo.png" alt="ximostock logo">
    </div>

    <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.73rem] mt-1 bg-gray-200">
    </div>

    <div class="flex mt-[0.08rem] ml-[0.3rem]">
        <div class="flex items-center relative left-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#D3D3D3"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </div>
        <input class="w-[30.5rem] h-[3.12rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
            style="font-size: 16px; border:1px solid #D3D3D3;" type="search" placeholder="Search...">
    </div>

    <div class="ml-auto mr-10 flex">
        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center">
            <button
                class="w-[10.53rem] h-[2.68rem] rounded-md bg-[#3dabd5] flex justify-center items-center text-white">
                <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                </div>

                <p class="flex font-[300] ">
                    Nieuw toevoegen
                </p>
            </button>
        </div>

        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center gap-3.5">
            <div class="w-[3.43rem] h-[3.43rem] bg-white rounded-full flex justify-center items-center"
                style="border: 1px solid #3dabd5;">
                <img class="w-[1.7rem] h-[2.1rem] flex mb-1" src="../images/user-icon.png" alt="user icon">
            </div>

            <button class="w-[9rem] h-[2.68rem] flex justify-center items-center rounded-md gap-2"
                style="border: 1px solid #717171">
                <p class="flex truncate  overflow-hidden whitespace-no-wrap w-[5.5rem]">
                    johan van Langedoorn
                </p>
                <img class="w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="../images/arrow-down-icon.png"
                    alt="user icon">
            </button>
        </div>

    </div>
</div>
