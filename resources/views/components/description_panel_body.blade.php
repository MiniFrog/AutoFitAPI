<li class="list-group-item">
    @include("components.column.column_name", ["columnType" => $info["type"], "columnName" => $info["name"]])
    @if (count($info["restrictions"]) != 0)
        <table class="table table-hover">
            <colgroup>
                <col style="width:40%"/>
                <col style="width:60%"/>
            </colgroup>
            <thead>
            @include("components.column.column_table_head")
            </thead>
            <tbody>
            @include("components.column.column_selection_type", ["type" => $info["type"]])
            @include("components.detail.description_size_display", [
                "size" => key_exists("size", $info["restrictions"]) ? $info["restrictions"]["size"] : null
            ])
            <tr>
                <td>
                    存在特性
                </td>
                <td>
                    @if (is_string($info["restrictions"]["exist"]))
                        {{$info["restrictions"]["exist"]}}
                    @else
                        {{$info["restrictions"]["exist"][0]}}
                        @for($i = 1; $i < count($info["restrictions"]["exist"]); $i++)
                            {{" 或 " . $info["restrictions"]["exist"][$i]}}
                        @endfor
                    @endif
                </td>
            </tr>
            @if (key_exists("length", $info["restrictions"]))
                <tr>
                    <td>
                        长度或大小
                    </td>
                    <td>
                        @if (is_string($info["restrictions"]["length"]))
                            固定{{$info["restrictions"]["length"]}}
                        @else
                            最小值 {{$info["restrictions"]["length"]['min'] ? $info["restrictions"]["length"]['min'] : "无"}} <br />
                            最大值 {{$info["restrictions"]["length"]['max'] ? $info["restrictions"]["length"]['max'] : "无"}} <br />
                        @endif
                    </td>
                </tr>
            @endif
            @include("components.column.column_extra", [
                "extra" => key_exists("extra", $info["restrictions"])  ? $info["restrictions"]["extra"] : null,
            ])
            </tbody>
        </table>
    @endif
</li>
