@foreach($detail as $column)
    @if($column["layer"] == "head")
        <li class="list-group-item">
            <div class="panel panel-default">
                @include('components.description_panel_head', ["info" => $column["info"]])
                <ul class="list-group">
                    @elseif($column["layer"] == "content")
                        @include('components.description_panel_body', ["info" => $column["info"]])
                    @else
                </ul>
            </div>
        </li>
    @endif
@endforeach
