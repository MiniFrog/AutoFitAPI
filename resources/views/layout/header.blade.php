<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url("/")}}">后台管理系统</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li {{$focus == "history" ? 'class="active"' : ""}}><a href="#">历史记录<span class="sr-only"></span></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">接口详情<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach ($APINames as $English => $Chinese)
                            <li><a href="{{url("description/$English")}}">{{$Chinese}}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">创建接口<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach ($APINames as $English => $Chinese)
                            <li><a href="{{url("create/$English")}}">{{$Chinese}}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">退出</a></li>
            </ul>
        </div>
    </div>
</nav>
