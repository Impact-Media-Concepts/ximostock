@extends('layouts.app')

@section('content')

    <category-overview :categories='@json($categoriesTree)'></category-overview>

@endsection
