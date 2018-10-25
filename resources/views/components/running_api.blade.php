<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">正在运行的接口</div>
    <div class="panel-body">
        <p>点击接口的创建时间可以查看接口的详细情况，可以在详情界面更改接口的配置</p>
        <p>请<strong>不要</strong>直接修改JSON文件，以免造成无法挽回的错误</p>
    </div>
    <!-- Table -->
    <table class="table table-hover">
        <thead>
        <tr>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>API名</th>
            <th>API版本</th>
            <th>使用编号</th>
            <th>临时编号</th>
        </tr>
        </thead>
        <tbody>
        @foreach($runningAPIs as $abstract)
            <tr>
                <td>{{$abstract->createdAt}}</td>
                <td>{{$abstract->updatedAt}}</td>
                <td>{{$abstract->APIName}}</td>
                <td>{{$abstract->version}}</td>
                <td>{{$abstract->uuid}}</td>
                <td>{{$abstract->code}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
