<h3>
    @if ($columnType == 0)
        <strong class="text-primary">{{$columnName}}</strong>
    @elseif ($columnType == 1)
        <strong class="text-success">{{$columnName}}</strong>
    @elseif ($columnType == 2)
        <strong class="text-warning">{{$columnName}}</strong>
    @else
        <strong class="text-info">{{$columnName}}</strong>
    @endif
</h3>
