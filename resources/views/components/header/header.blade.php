@props(['search' => null, 'workspaces', 'activeWorkspace' => null])

<div class="w-full h-[5.05rem] absolute shadow-[0_1px_12px_-5px_rgba(0,0,0,0.3)] bg-white flex items-center z-[998]">
    <button class="px-11 pt-1 relative left-[0.08rem]">
        <a href="{{ url('/dashboard') }}">
            <img class="select-none w-[10.75] h-[1.31rem] flex" src="../images/ximostock-logo.png" alt="ximostock logo">
        </a>
    </button>

    <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.73rem] mt-1 bg-gray-200">
    </div>

    <div class="flex mt-[0.08rem] ml-[0.3rem] items-center">
        <button type="submit" class="relative flex items-center left-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="#D3D3D3" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>
        <input id="searchBar" class="w-[30.5rem] h-[3.12rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
            style="font-size: 16px; border:1px solid #D3D3D3;" name="search" type="text" placeholder="Search..."
            autocomplete="off" value="{{ $search }}">
    </div>

    <div class="flex ml-auto mr-10">
        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center">
            <div x-data="{ open: false, selectedProperty: '' }" class="relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
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

                <div x-cloak x-show="open"
                    class="absolute flex-col justify-center items-center w-[10.43rem] bg-[#3dabd5] divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 left-6 top-[3.2rem]">
                    <ul class="dropdown-content">
                        <li class="dropdown flex justify-start">
                            <div
                                class="hover:rounded-t-lg duration-100 block w-[10.43rem] px-4 py-2 text-[14px] text-white focus:outline-none flex items-center justify-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <p class="pl-[0.2rem]">
                                    Products
                                </p>
                            </div>
                            <ul class="dropdown-content absolute hidden rounded-md right-[1rem] pr-[9.8rem] w-[18rem]">
                                <div class="shadow-lg rounded-md ">
                                    <li class="rounded-md">
                                        <a class="text-[14px] shadow-sm flex items-center rounded-t-lg text-white text-left bg-[#3dabd5] hover:bg-[#3999BE] hover:rounded-t-lg py-2 px-4 block whitespace-no-wrap"
                                            href="/products/create">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="white" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            <p class="pl-[0.2rem]">
                                                Simpel
                                            </p>
                                        </a>
                                    </li>
                                    <li class="rounded-md">
                                        <a class="text-[14px] shadow-sm flex items-center rounded-b-lg text-white text-left bg-[#3dabd5] hover:bg-[#3999BE] hover:rounded-b-lg py-2 px-4 block whitespace-no-wrap"
                                            href="/variant/create">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="white" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            <p class="pl-[0.2rem]">
                                                Variable
                                            </p>
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                    <ul class="flex-col !border-t-0">
                        <li>
                            <a href="/categories/create"
                                class="hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 hover:bg-[#3999BE] focus:outline-none flex items-center justify-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span
                                    class="text-[14px] text-white flex items-center justify-center pl-[0.2rem]">Categorie</span>
                            </a>
                        </li>
                        <li>
                            <a href="/"
                                class="hover:bg-[#3999BE] hover:rounded-b-lg  duration-100 block w-[10.43rem] px-4 py-2 hover:bg-[#3999BE] focus:outline-none flex items-center justify-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-[14px] text-white flex items-center justify-center pl-[0.2rem]">Eigenschap</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        @can('index-workspaces')
            <div x-data="{ open: false, selectedProperty: '' }" class="relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;"
                    title="Workspaces"
                    class="flex gap-[0.7rem] items-center z-20 w-[9.18rem] px-[1.08rem] h-[2.81rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid #717171" type="button" @click.away="open = false">
                    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                        <img class="select-none w-[1.2rem] h-[1.2rem] flex"
                            src="../images/workspaces-icon.png" alt="workspaces icon">
                    </div>
                    <span class="text-left text-[14px] text-gray-700 line-clamp-1 relative right-2">
                        Workspaces
                    </span>
                </button>
                <div class="flex justify-center">
                    <div x-cloak x-show="open"
                        class="absolute mr-[13.7rem] flex-col justify-center items-center h-[36.18rem] max-h-[36.16rem] overflow-y-auto w-[16.56rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.6rem]"
                        style="border: 1px solid #F0F0F0;">
                        <div class="flex items-center justify-center">
                            <input @click.stop class="sticky workspace-search-input w-[14.06rem] h-[2.5rem] rounded-md mt-[1rem] pl-[1rem]" style="border: 1px solid #D3D3D3;" type="text" id="workspaceInput" placeholder="Zoeken" />
                        </div>

                        <div class="items-center border-none mt-[1rem]" id="workspaceList">
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center gap-3.5">
            <a href="/user" class="w-[3.43rem] h-[3.43rem] bg-white rounded-full flex justify-center items-center"
                style="border: 1px solid #3dabd5;">
                <img class="select-none w-[1.7rem] h-[2.1rem] flex mb-1" src="../images/user-icon.png"
                    alt="user icon">
            </a>

            <div x-data="{ open: false, selectedProperty: '' }" class="relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;"
                    class="flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid #717171" type="button" @click.away="open = false">
                    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="white" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <span class="w-52 text-left text-[14px] text-gray-700 line-clamp-1 relative right-2"
                        title="{{ Auth::user()->name }}">
                        {{ Auth::user()->name }}
                    </span>
                    <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]"
                        src="../images/arrow-down-icon.png" alt="arrow down">
                </button>

                <div x-cloak x-show="open"
                    class="absolute flex justify-center items-center h-[5.37rem] w-[10.43rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]"
                    style="border: 1px solid #F0F0F0;">
                    <ul>
                        <li>
                            <a href="/settings">
                                <button @click="selectedProperty = 'Instellingen'; open = false;"
                                    class="hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-center">
                                    <img class="select-none h-5.5 w-5.5 pr-3" src="../images/gray-settings.png"
                                        class="w-4 h-4 mr-2" alt="Instellingen Icon">
                                    <span class="flex items-center justify-center pr-3">Instellingen</span>
                                </button>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button @click="selectedProperty = 'Uitloggen'; open = false;"
                                    class="hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-center">
                                    <img class="select-none h-5.5 w-5.5 pr-3" src="../images/log-out-icon.png"
                                        class="w-4 h-4 mr-2" alt="Uitloggen Icon">
                                    <span class="flex items-center justify-center pr-3">Uitloggen</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@can('index-workspaces')
    <x-workspaces-data :workspaces="$workspaces"/>
@endcan

<script>
    function renderWorkspaces() {
        const workspaceList = document.getElementById('workspaceList');

        workspaceList.innerHTML = '';
        
        workspacesData.forEach(workspace => {
            const activeWorkspace = {{ $activeWorkspace ?? 'null' }};
            const li = document.createElement('li');
            li.classList.add('flex', 'items-center', 'justify-start');
            li.id = `li_${workspace.id}`;

            const a = document.createElement('a');
            a.href = `?workspace=${workspace.id}`;

            const button = document.createElement('button');
            button.classList.add('hover:bg-[#3999BE]', 'duration-100', 'block', 'w-[15.5rem]', 'h-[2.3rem]', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100', 'focus:outline-none', 'flex', 'justify-start', 'items-center');
            button.addEventListener('click', () => {
                selectedProperty = 'Instellingen';
                open = false;
            });

            const span = document.createElement('span');
            span.classList.add('flex', 'items-center', 'justify-start', 'pr-3', 'text-[#717171]');
            span.textContent = workspace.name;

            button.appendChild(span);

            // Check if the workspace id matches the active workspace id from props
            if (workspace.id === activeWorkspace) {
                const div = document.createElement('div');
                div.classList.add("w-full", "flex", "items-center", "justify-end")
                const span = document.createElement('span');
                span.style.width = '0.5rem';
                span.style.height = '0.5rem';
                span.style.backgroundColor = '#3999BE';
                span.style.borderRadius= "50%";
                div.appendChild(span);
                button.appendChild(div);
            }

            a.appendChild(button);
            li.appendChild(a);
            workspaceList.appendChild(li);
        });
    }

    renderWorkspaces();
    
    function searchWorkspaces(workspaceSearchText) {
        workspacesData.forEach((workspace) => {
        const li = document.getElementById(`li_${workspace.id}`);
        if (
            !workspaceSearchText ||
            workspace.name.toLowerCase().includes(workspaceSearchText.toLowerCase())
        ) {
            li.classList.remove("hidden");
        } else {
            li.classList.add("hidden");
        }
        });
    }

    const workspaceSearchInput = document.getElementById("workspaceInput");
    workspaceSearchInput.addEventListener("input", () => {
        const workspaceSearchText = workspaceSearchInput.value.trim();

        // Render Workspaces if search input is empty
        if (!workspaceSearchText) {
            renderWorkspaces(); //vervang met show all
        } else {
            searchWorkspaces(workspaceSearchText);
        }
    });
</script>
