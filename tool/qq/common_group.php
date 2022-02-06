<?php require_once '../../include/front/header.php'; ?>
        
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
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">查询</button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        
        <div id="result" style="display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                <p id="result_text"></p>
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
                        result = document.getElementById('result'),
                        result_text = document.getElementById('result_text');
                    
                    submit_button.disabled = true;
                    result_text.innerHTML = '';
                    result.style.display = 'none';
                    
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
                        if(!qq_number){
                            layer.close(load);
                            layer.msg('请输入QQ号');
                            submit_button.disabled = false;
                        } else {
                            axios.get('https://api.heroa.cn:3403/qq/common_group/?qq_number=' + qq_number + '&p_uin=' + get_cookie('p_uin') + '&p_skey=' + get_cookie('p_skey'))
                                .then(function(data) {
                                    data = data.data.information
                                    if (typeof data == 'object') {
                                        for (let data_count in data) {
                                            result_text.innerHTML += data[data_count] + ' ';
                                        }
                                        layer.close(load);
                                        result.style.display = "block";
                                        submit_button.disabled = false;
                                    } else {
                                        layer.close(load);
                                        layer.alert(data);
                                        submit_button.disabled = false;
                                    }
                            });
                        }
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>