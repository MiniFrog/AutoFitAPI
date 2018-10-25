@if ($extra != null)
    @foreach($extra as $key => $value)
        <tr>
            <td>
                {{$key}}
            </td>
            <td>
                {{$value}}
            </td>
        </tr>
    @endforeach
@endif
