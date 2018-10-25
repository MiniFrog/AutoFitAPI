<tr>
    <td>
        重复次数
    </td>
    <td>
        @if ($size == null)
            无重复
        @elseif (is_string($size))
            {{$size}}
        @else
            最少 {{$size["min"] ? $size["min"] : "无"}} <br />
            最多 {{$size["max"] ? $size["max"] : "无"}}
        @endif
    </td>
</tr>
