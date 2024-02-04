@extends('layouts.app')
@section('custom_css')
    <!-- DataTables CSS with Bootstrap theme -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    @include('components.castomer.castomer-list')
    @include('components.castomer.castomer-create')
    @include('components.castomer.castomer-delete')
    @include('components.castomer.castomer-update')
@endsection
