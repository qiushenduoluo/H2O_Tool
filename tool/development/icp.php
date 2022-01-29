<?php require_once '../../include/front/header.php'; ?>
    
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">域名</label>
                        <div class="layui-input-block">
                            <input type="text" name="domain" autocomplete="off" placeholder="请输入域名" class="layui-input" lay-verify="required"/>
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
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        domain = data.domain,
                        result = document.getElementById('result'),
                        result_table = document.getElementById('result_table');
                    
                    submit_button.disabled = true;
                    $('tr:gt(0)').remove();
                    result.style.display = 'none';
                    
                    if(!domain){
                        layer.close(load);
                        layer.msg('请输入域名');
                        submit_button.disabled = false;
                    } else {
                        axios.get('https://api.heroa.cn:3403/development/icp/?domain=' + domain)
                            .then(function(data) {
                                data = data.data.information;
                                if (typeof data == 'object') {
                                    result_table.innerHTML += ' \
                                        <tr><td>主办单位名称</td><td>' + data.sponsor_name + '</td></tr> \
                                        <tr><td>主办单位性质</td><td>' + data.sponsor_nature + '</td></tr> \
                                        <tr><td>备案号</td><td>' + data.number + '</td></tr> \
                                        <tr><td>网址名称</td><td>' + data.website_name + '</td></tr> \
                                        <tr><td>首页网址</td><td>' + data.index_url + '</td></tr> \
                                        <tr><td>审核时间</td><td>' + data.time + '</td></tr> \
                                    ';
                                    layer.close(load);
                                    result.style.display = "block";
                                    submit_button.disabled = false;
                                } else {
                                    layer.close(load);
                                    layer.alert('未备案');
                                    submit_button.disabled = false;
                                }
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>