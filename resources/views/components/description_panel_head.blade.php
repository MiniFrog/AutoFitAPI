<div class="panel-heading">
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
            </tbody>
        </table>
    @endif
</div>
