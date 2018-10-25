<tr>
    <td>
        存在特性
    </td>
    <td>
        @if (is_string($exist))
            {{$exist}}
        @else
            <select class="form-control" id="{{$id}}-exist">
                @foreach($exist as $type)
                    <option>{{$type}}</option>
                @endforeach
            </select>
        @endif
    </td>
</tr>
