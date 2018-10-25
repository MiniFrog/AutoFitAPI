<!DOCTYPE html>
<html>
<head>
    <title>后台管理系统 @yield('title')</title>
    @include('setting/bootstrap')
</head>
<body>
@include('layout/header', ["APINames" => $APINames, "focus" => null])
<div class="row clearfix">
    <div class="col-md-2 column">
    </div>
    <div class="col-md-8 column">
        @yield('content')
    </div>
    <div class="col-md-2 column">
    </div>
</div>
@include('layout.footer')
</body>
</html>
