@extends('layouts.app')

@section('content')
<div id="app">
    <saleschannel-overview
        :saleschannels='@json($saleschannels)'>
    </saleschannel-overview>
</div>
@endsection
