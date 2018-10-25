@extends('layout.template')

@section('title', '创建新接口')

@section('content')
    <script type="text/javascript">
        function columnSelected(id) {
            let select = document.getElementById(id);
            return select.checked;
        }

        function getMinRepeatTimes(id) {
            return parseInt(document.getElementById(id + "-size-min").value);
        }

        function getMaxRepeatTimes(id) {
            return parseInt(document.getElementById(id + "-size-max").value);
        }

        function getMinLength(id) {
            return parseInt(document.getElementById(id + "-length-min").value);
        }

        function getMaxLength(id) {
            return parseInt(document.getElementById(id + "-length-max").value);
        }

        function getExistSelection(id) {
            return parseInt(document.getElementById(id + "-exist").value);
        }

        function validate(id, min, max, restrictionMin, restrictionMax) {
            if (min === "" || max === "") {
                alert("字段" + id + "选择了但是没有确定重复次数");
                return false;
            }
            if (isNaN(restrictionMin)) {
                if (!eval(min + restrictionMin)) {
                    alert("字段" + id + "最小值不符合限定 要求" + restrictionMin);
                    return false;
                }
            }
            if (isNaN(restrictionMax)) {
                if (!eval(max + restrictionMax)) {
                    alert("字段" + id + "最大值不符合限定 要求" + restrictionMax);
                    return false;
                }
            }
            if (min > max) {
                alert("字段" + id + "最大值小于最小值");
                return false;
            }
            return true;
        }

        function collect(detail = {!! $detailJSArray !!}) {
            console.log(parseInt(""));
            let status = 0;
            let selected = [];
            for (let i = 0, len = detail.length; i < len; i++) {
                let id = detail[i].id;
                let layer = detail[i].layer;
                if (layer === "tail") {
                    selected.push(detail[i]);
                    status = 0;
                    continue;
                }
                if (status === 1) {
                    continue;
                }
                let type = detail[i].info.type;
                let size = detail[i].info.restrictions.size;
                switch (layer) {
                    case "head":
                        if (type === 3 || type === 2) {
                            if (!columnSelected(id)) {
                                status = 1;
                                continue;
                            }
                        }
                        if ($.isPlainObject(size) && (isNaN(size.min) || isNaN(size.max))) {
                            let min = getMinRepeatTimes(id);
                            let max = getMaxRepeatTimes(id);
                            if (!validate(id, min, max, size.min, size.max)) {
                                return;
                            }
                            if (min === max) {
                                detail[i].info.restrictions.size = min;
                            } else {
                                detail[i].info.restrictions.size.min = min;
                                detail[i].info.restrictions.size.max = max;
                            }
                        }
                        break;
                    case "content":
                        let exist = detail[i].info.restrictions.exist;
                        let length = detail[i].info.restrictions.length;
                        if (type === 3 || type === 2) {
                            if (!columnSelected(id)) {
                                continue;
                            }
                        }
                        if ($.isPlainObject(size) && (isNaN(size.min) || isNaN(size.max))) {
                            let min = getMinRepeatTimes(id);
                            let max = getMaxRepeatTimes(id);
                            if (!validate(id, min, max, size.min, size.max)) {
                                return;
                            }
                            if (min === max) {
                                detail[i].info.restrictions.size = min;
                            } else {
                                detail[i].info.restrictions.size.min = min;
                                detail[i].info.restrictions.size.max = max;
                            }
                        }
                        if ($.isPlainObject(length) && (isNaN(length.min) || isNaN(length.max))) {
                            let min = getMinLength(id);
                            let max = getMaxLength(id);
                            if (!validate(id, min, max, length.min, length.max)) {
                                return;
                            }
                            if (min === max) {
                                detail[i].info.restrictions.length = min;
                            } else {
                                detail[i].info.restrictions.length.min = min;
                                detail[i].info.restrictions.length.max = max;
                            }
                        }
                        if ($.isArray(exist)) {
                            detail[i].info.restrictions.exist = getExistSelection(id);
                        }
                }
                selected.push(detail[i]);
            }
            return selected;
        }

        function create_rent() {
            let beans = collect();
            if (beans !== undefined) {
                document.getElementById("storage_input").value = JSON.stringify(beans);
                document.getElementById("storage").submit()
            }
        }

    </script>
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
                            <a href="{{url("/create/$currentAPI/$version")}}">{{$version}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <ul class="list-group">
            @include("components/create_columns", ["description" => $detail])
        </ul>
    </div>
    <div class="col-md-4 column">
    </div>
    <div class="col-md-4 column">
        <button type="button" class="btn btn-info btn-lg btn-block" onclick="create_rent()">确认无误</button>
    </div>
    <form id="storage" method="post" action="{{url("/create/$currentAPI/$version")}}">
        <input id="storage_input" type="hidden" name="detail" value="">
        {{ csrf_field() }}
    </form>
    <div class="col-md-4 column">
    </div>
@endsection
