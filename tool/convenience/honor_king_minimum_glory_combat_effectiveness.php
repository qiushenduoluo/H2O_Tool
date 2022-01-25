<?php require_once '../../include/front/header.php'; ?>
    
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
            
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">英雄名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" autocomplete="off" placeholder="请输入英雄名称" class="layui-input" lay-verify="required"/>
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
        
        <div id="result" style="overflow: auto; display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                
                <table id="result_table" class="layui-table" lay-skin="row" lay-even="">
                    <colgroup>
                        <col width="30%"/>
                        <col width="35%"/>
                        <col width="35%"/>
                        <col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>类型</th>
                            <th>分数</th>
                            <th>地区</th>
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
                        name = data.name,
                        result = document.getElementById('result'),
                        result_table = document.getElementById('result_table');
                    
                    submit_button.disabled = true;
                    $('tr:gt(0)').remove();
                    result.style.display = 'none';
                    
                    if(!name){
                        layer.close(load);
                        layer.msg('请输入英雄名称');
                        submit_button.disabled = false;
                    } else {
                        axios.get('https://api.heroa.cn:3403/convenience/honor_king_minimum_glory_combat_effectiveness/?type=sapi&name=' + name)
                            .then(function(data) {
                                data = data.data.information;
                                if (typeof data == 'object') {
                                    ranking = data.ranking;
                                    qq = ranking.qq;
                                    qq_district = qq.district;
                                    qq_city = qq.city;
                                    qq_province = qq.province;
                                    wechat = ranking.qq;
                                    wechat_district = wechat.district;
                                    wechat_city = wechat.city;
                                    wechat_province = wechat.province;
                                    result_table.innerHTML += ' \
                                        <tr><td>名称</td><td>' + data.name + '</td><td>' + data.name + '</td></tr> \
                                        <tr><td>更新日期</td><td>' + data.update_date + '</td><td>' + data.update_date + '</td></tr> \
                                        <tr><td>QQ县区</td><td>' + qq_district.score + '</td><td>' + qq_district.region + '</td></tr> \
                                        <tr><td>QQ市区</td><td>' + qq_city.score + '</td><td>' + qq_city.region + '</td></tr> \
                                        <tr><td>QQ省区</td><td>' + qq_province.score + '</td><td>' + qq_province.region + '</td></tr> \
                                        <tr><td>微信县区</td><td>' + wechat_district.score + '</td><td>' + wechat_district.region + '</td></tr> \
                                        <tr><td>微信市区</td><td>' + wechat_city.score + '</td><td>' + wechat_city.region + '</td></tr> \
                                        <tr><td>微信省区</td><td>' + wechat_province.score + '</td><td>' + wechat_province.region + '</td></tr> \
                                    ';
                                    layer.close(load);
                                    result.style.display = "block";
                                    submit_button.disabled = false;
                                } else {
                                    layer.close(load);
                                    layer.alert('英雄不存在');
                                    submit_button.disabled = false;
                                }
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>