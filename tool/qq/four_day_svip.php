<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                每个QQ账户只能领取一次.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">领取</button>
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
                    
                    if (get_cookie('state') != '状态:已登录') {
                        layer.close(load);
                        layer.alert('未登录');
                        submit_button.disabled = false;
                    } else if (get_cookie('permission_state') != '状态:已登录') {
                        layer.close(load);
                        layer.alert('权限未登录');
                        submit_button.disabled = false;
                    } else {
                        axios.get('../../include/back/tool_api/4svip.php?uin=' + get_cookie('qq_number').replace('账号:', '') + '&skey=' + get_cookie('skey') + '&pskey=' + get_cookie('p_skey'))
                            .then(function(data) {
                                data = data.data.msg
                                layer.close(load);
                                layer.msg(data);
                                submit_button.disabled = false;
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>