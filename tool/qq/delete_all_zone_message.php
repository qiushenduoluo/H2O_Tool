<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.不能保证每次都能清空完.<br/>
                2.如没有清空完多试几次.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">清空</button>
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
                    
                    <?php
                        if ($verification['open']) {
                            echo '
                                if (!is_verification_success()) {
                                    layer.close(load);
                                    layer.msg("验证未登录");
                                    submit_button.disabled = false;
                                    return false;
                                }
                            ';
                        }
                    ?>
                    
                    if (!is_qq_login()) {
                        layer.close(load);
                        layer.msg('QQ未登录');
                        submit_button.disabled = false;
                    } else {
                        axios.get('https://api.heroa.cn:3403/qq/zone_message/?type=delete_all&p_uin=' + get_cookie('p_uin') + '&p_skey=' + get_cookie('p_skey'))
                            .then(function(data) {
                                data = data.data.information
                                if (typeof data == 'object') {
                                    data = '未删除说说的ID:' + data;
                                }
                                layer.close(load);
                                layer.alert(data);
                                submit_button.disabled = false;
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>