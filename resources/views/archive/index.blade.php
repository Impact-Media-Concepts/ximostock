@extends('layouts.app')

@section('content')

    <archive-overview
        :items='@json($items)'>
    </archive-overview>
@endsection