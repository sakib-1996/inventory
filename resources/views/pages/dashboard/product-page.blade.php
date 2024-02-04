@extends('layouts.app')
@section('custom_css')
    <!-- DataTables CSS with Bootstrap theme -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    @include('components.product.product-list')
    @include('components.product.product-create')
    @include('components.product.product-delete')
    @include('components.product.product-update')

@endsection


