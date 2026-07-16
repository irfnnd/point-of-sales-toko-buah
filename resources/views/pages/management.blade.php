@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="mb-0">{{ $description }}</p>
        </div>
    </div>
@stop
