<tr>

    <td>
        重复次数
    </td>
    <td>
        @if ($size == null)
            无重复
        @elseif (is_string($size))
            固定 {{$size}}
        @else
            @if (is_numeric($size["min"]) && is_numeric($size["max"]))
                最少 {{$size["min"]}} <br/>
                最多 {{$size["max"]}}
            @else
                <div class="row">
                    <div class="col-lg-6">
                        @if (! is_numeric($size["min"]))
                            <div class="input-group">
                                <span class="input-group-addon">最小值</span>
                                <input type="text" class="form-control"
                                       id="{{$id}}-size-min"
                                       placeholder="{{$size["min"]}}"
                                       aria-describedby="sizing-addon2">
                            </div>
                        @else
                            <div class="input-group">
                                <span class="input-group-addon">最小值</span>
                                <input type="text" class="form-control"
                                       id="{{$id}}-size-min"
                                       value="{{$size["min"]}}"
                                       aria-describedby="sizing-addon2" disabled>
                            </div>
                        @endif
                    </div><!-- /input-group -->
                    <div class="col-lg-6">
                        @if(! is_numeric($size["max"]))
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">最大值</span>
                                <input type="text" class="form-control"
                                       placeholder="{{$size["max"]}}"
                                       id="{{$id}}-size-max"
                                       aria-describedby="sizing-addon2">
                            </div>
                        @else
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">最大值</span>
                                <input type="text" class="form-control"
                                       id="{{$id}}-size-max"
                                       value="{{$size["max"]}}"
                                       aria-describedby="sizing-addon2" disabled>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </td>
</tr>
