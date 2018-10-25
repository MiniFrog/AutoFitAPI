<div class="panel-heading">
    @include("components.column.column_name", ["columnType" => $info["type"], "columnName" => $info["name"]])
    @include("components.column.column_selection_checkbox", ["type" => $info["type"], "id" => $id])
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
            @include("components.create.create_size_setting", [
                "size" => key_exists("size", $info["restrictions"]) ?  $info["restrictions"]["size"] : null,
                "id" => $id
            ])
            </tbody>
        </table>
    @endif
</div>
