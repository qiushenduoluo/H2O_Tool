<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.双方必须是好友.<br/>
                2.次数范围为1-10.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">QQ号</label>
                        <div class="layui-input-block">
                            <input type="text" name="qq_number" autocomplete="off" placeholder="请输入QQ号" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">描述</label>
                        <div class="layui-input-block">
                            <input type="text" name="description" autocomplete="off" placeholder="请输入描述" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">次数</label>
                        <div class="layui-input-block">
                            <input type="number" name="times" autocomplete="off" placeholder="请输入次数" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">提交</button>
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
                        qq_number = data.qq_number,
                        description = data.description,
                        times = data.times;
                    
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
                        if(!qq_number || !description || !times){
                            layer.close(load);
                            layer.msg('请输入完整信息');
                            submit_button.disabled = false;
                        } else {
                            times = parseInt(times);
                            if (0 > times || 10 < times) {
                                layer.close(load);
                                layer.msg('次数错误');
                                submit_button.disabled = false;
                            } else {
                                for (var count = 0; count < times; count++) {
                                    setTimeout(function () {
                                        axios.get('https://api.heroa.cn:3403/qq/zone_gift/?qq_number=' + qq_number + '&id=205462&description=' + description + '&p_uin=' + get_cookie('p_uin') + '&p_skey=' + get_cookie('p_skey'));
                                    }, 300)
                                }
                                layer.close(load);
                                layer.msg('提交成功');
                                submit_button.disabled = false;
                            }
                        }
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>