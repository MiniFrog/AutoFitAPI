@extends('layout.template')

@section('title', '接口详情')

@section('content')
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    版本编号: {{$currentVersion}} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @foreach($versions as $version)
                        <li>
                            <a href="{{url("/detail/$currentAPI/$version")}}">{{$version}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <ul class="list-group">
            @include("components.description_columns", ["description" => $detail])
        </ul>
    </div>
@endsection
