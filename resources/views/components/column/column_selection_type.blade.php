<tr>
    <td>
        选择特性
    </td>
    <td>
        @if ($type == 0)
            必须选择
        @elseif ($type == 1)
            必须选择 | 多选
        @elseif ($type == 2)
            可以选择
        @elseif ($type == 3)
            可以选择 | 多选
        @endif
    </td>
</tr>
