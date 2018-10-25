@extends('layout.template')

@section('title', '接口详情')

@section('content')
    @include('components.running_api', ['runningAPIs' => $runningAPIs])
@endsection
