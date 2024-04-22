<x-layout._header-dependencies :sidenavActive="$sidenavActive"/>
<body class="bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>

    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
        </div>
        <div class=" pt-20 ">
            <form action="/properties/bulkdelete" method="POST">
                @csrf
                <ul>
                    @foreach ($properties as $property)
                        <li>
                            <input type="checkbox" name="properties[]" value="{{$property->id}}"/>
                            {{ 'name: ' . $property->name . '  type: ' . $property->type }}
                            <a href="/properties/{{ $property->id }}">property</a>
                        </li>
                    @endforeach
                </ul>
                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="submit" value="bulk delete"/>
            </form>
        </div>

    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>

</html>
