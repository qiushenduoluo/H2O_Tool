<?php require_once '../../include/front/header.php'; ?>
    
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">我的UA</label>
                        <div class="layui-input-block">
                            <input type="text" id="my_user_agent" autocomplete="off" class="layui-input" disabled="disabled"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">UA</label>
                        <div class="layui-input-block">
                            <input type="text" name="user_agent" autocomplete="off" placeholder="请输入UA" class="layui-input" lay-verify="required"/>
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
                
                <table id="result_table" class="layui-table" lay-skin="row" lay-even="">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                        <col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>键</th>
                            <th>值</th>
                        </tr>
                    </thead>
                </table>
                
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function() {
                document.getElementById('my_user_agent').value = navigator.userAgent;
            });
            
            user_agent = get_request_parameter('user_agent');
            
            if (user_agent) {
                submit_d(user_agent);
            }
            
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var user_agent = data.user_agent;
                    
                    submit_d(user_agent);
                    
                    return false;
                });
            });
            
            function submit_d(user_agent='') {
                var load = layer.load(0, {shade: false}),
                    submit_button = document.getElementById('submit_button'),
                    result = document.getElementById('result'),
                    result_table = document.getElementById('result_table');
                
                submit_button.disabled = true;
                $('tr:gt(0)').remove();
                result.style.display = 'none';
                
                if(!user_agent){
                    layer.close(load);
                    layer.msg('请输入UA');
                    submit_button.disabled = false;
                } else {
                    axios.get('https://api.heroa.cn:3403/development/user_agent_analysis/?user_agent=' + user_agent)
                        .then(function(data) {
                            data = data.data.information;
                            result_table.innerHTML += ' \
                                <tr><td>浏览器名称</td><td>' + data.browser.name + '</td></tr> \
                                <tr><td>浏览器版本号</td><td>' + data.browser.version + '</td></tr> \
                                <tr><td>操作系统名称</td><td>' + data.operating_system.name + '</td></tr> \
                                <tr><td>操作系统版本号</td><td>' + data.operating_system.version + '</td></tr> \
                                <tr><td>设备品牌</td><td>' + data.device.brand + '</td></tr> \
                                <tr><td>设备型号</td><td>' + data.device.model + '</td></tr> \
                            ';
                            layer.close(load);
                            result.style.display = "block";
                            submit_button.disabled = false;
                    });
                }
            }
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>