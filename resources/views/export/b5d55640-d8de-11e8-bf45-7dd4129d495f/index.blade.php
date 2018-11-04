<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>队伍</th>
        <th>姓名</th>
        <th>专业</th>
        <th>班级</th>
        <th>宿舍</th>
        <th>电话</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $team)
        <tr>
            <td rowspan="3">
                {{$team["team"]["name"]}}
            </td>
            @include("export.b5d55640-d8de-11e8-bf45-7dd4129d495f.person", ["person" => $team["leader"]]);
        </tr>
        @foreach($team["members"] as $member)
            <tr>
                @include("export.b5d55640-d8de-11e8-bf45-7dd4129d495f.person", ["person" => $member]);
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</body>
</html>
