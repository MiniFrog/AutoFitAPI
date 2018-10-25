@foreach($detail as $column)
    @if($column["layer"] == "head")
        <li class="list-group-item">
            <div class="panel panel-default">
                @include('components.create_panel_head', ["info" => $column["info"], "id" => $column["id"]])
                <ul class="list-group">
                    @elseif($column["layer"] == "content")
                        @include('components.create_panel_body', ["info" => $column["info"], "id" => $column["id"]])
                    @else
                </ul>
            </div>
        </li>
    @endif
@endforeach
