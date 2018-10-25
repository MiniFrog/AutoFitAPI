@if ($length != null)
    <tr>
        <td>
            长度或大小
        </td>
        <td>
            @if (is_string($length))
                固定{{$length}}
            @else
                @if (is_numeric($length["min"]) && is_numeric($length["max"]))
                    最少 {{$length["min"]}} <br/>
                    最多 {{$length["max"]}}
                @else
                    <div class="row">
                        <div class="col-lg-6">
                            @if (! is_numeric($length["min"]))
                                <div class="input-group">
                                    <span class="input-group-addon">最小值</span>
                                    <input type="text" class="form-control"
                                           id="{{$id}}-length-min"
                                           placeholder="{{$length["min"]}}"
                                           aria-describedby="sizing-addon2">
                                </div>
                            @else
                                <div class="input-group">
                                    <span class="input-group-addon">最小值</span>
                                    <input type="text" class="form-control"
                                           id="{{$id}}-length-min"
                                           value="{{$length["min"]}}"
                                           aria-describedby="sizing-addon2" disabled>
                                </div>
                            @endif
                        </div><!-- /input-group -->
                        <div class="col-lg-6">
                            @if(! is_numeric($length["max"]))
                                <div class="input-group">
                                    <span class="input-group-addon" id="sizing-addon2">最大值</span>
                                    <input type="text" class="form-control"
                                           placeholder="{{$length["max"]}}"
                                           id="{{$id}}-length-max"
                                           aria-describedby="sizing-addon2">
                                </div>
                            @else
                                <div class="input-group">
                                    <span class="input-group-addon" id="sizing-addon2">最大值</span>
                                    <input type="text" class="form-control"
                                           id="{{$id}}-length-max"
                                           value="{{$length["max"]}}"
                                           aria-describedby="sizing-addon2" disabled>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </td>
    </tr>
@endif
