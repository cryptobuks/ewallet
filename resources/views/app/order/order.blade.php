@extends('adminlte::page')

@section('title', 'All orders')

{{--@section('content_header')--}}
    {{--<h1>All orders</h1>--}}
{{--@stop--}}

@section('content')
    <order-index-component></order-index-component>
@stop
