<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.<a href="http://t.cn/A6Z4KWp2" target="_blank">点击此处进入QQ小程序</a><br/>
                2.点击设备信息.<br/>
                3.安卓的IMEI为androidID,苹果的为msg_identifier.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机型号</label>
                        <div class="layui-input-block">
                            <select name="model" lay-verify="required">
                                <option value="iphone13promax" selected="">iPhone 13 Pro Max</option>
                                <option value="iphone4">iPhone 4</option>
                                <option value="ipad">iPad</option>
                                <option value="iqooneo">iQOO Neo</option>
                          </select>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">IMEI</label>
                        <div class="layui-input-block">
                            <input type="text" name="imei" autocomplete="off" placeholder="请输入IMEI" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">修改</button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        
        <script type="text/javascript">
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        model = data.model,
                        imei = data.imei;
                    
                    submit_button.disabled = true;
                    
                    if (!is_login()) {
                        layer.close(load);
                        layer.alert('未登录');
                        submit_button.disabled = false;
                    } else {
                        if(!model || !imei){
                            layer.close(load);
                            layer.msg('请输入完整信息');
                            submit_button.disabled = false;
                        }
                        else{
                            axios.get('https://api.heroa.cn:3403/qq/revise_online_model/?model=' + model + '&imei=' + imei + '&uin=' + get_cookie('uin') + '&skey=' + get_cookie('skey') + '&pt4_token=' + get_cookie('pt4_token'))
                                .then(function(data) {
                                    data = data.data.information
                                    layer.close(load);
                                    layer.msg(data);
                                    submit_button.disabled = false;
                            });
                        }
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>