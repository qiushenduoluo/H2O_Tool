<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">QQ群号</label>
                        <div class="layui-input-block">
                            <input type="text" name="qq_group_number" autocomplete="off" placeholder="请输入QQ群号" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">查询</button>
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
                        qq_group_number = data.qq_group_number;
                    
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
                    
                    if(!qq_group_number){
                        layer.close(load);
                        layer.msg('请输入QQ群号');
                        submit_button.disabled = false;
                    } else {
                        axios.get('https://api.heroa.cn:3403/qq/add_group/?format=json&qq_group_number=' + qq_group_number)
                            .then(function(data) {
                                data = data.data.information.url;
                                layer.close(load);
                                layer.open({
                                    content: '加群链接:' + data
                                    ,btn: ['复制', '取消']
                                    ,yes: function(index, layero){
                                        copy_text(data);
                                        layer.msg('复制成功');
                                    }
                                });
                                submit_button.disabled = false;
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>