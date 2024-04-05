@props(['workspaces'])

<script>
    let workspacesData = [
        @foreach ( $workspaces as $workspace)
            {
                id:{{$workspace->id}},
                name: "{{$workspace->name}}"
            },
        @endforeach
  ];
</script>
