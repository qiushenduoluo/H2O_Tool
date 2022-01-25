<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
            
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item" pane="">
                        <label class="layui-form-label">计算类型</label>
                        <div class="layui-input-block">
                            <input type="radio" name="calculation_type" value="appellation" title="称谓" checked=""/>
                            <input type="radio" name="calculation_type" value="relation" title="关系"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item" pane="">
                        <label class="layui-form-label">称谓类型</label>
                        <div class="layui-input-block">
                            <input type="radio" name="appellation_type" value="you" title="你" checked=""/>
                            <input type="radio" name="appellation_type" value="i" title="我"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item" pane="">
                        <label class="layui-form-label">性别</label>
                        <div class="layui-input-block">
                            <input type="radio" name="sex" value="male" title="男" checked=""/>
                            <input type="radio" name="sex" value="female" title="女"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">原关系</label>
                        <div class="layui-input-block">
                            <input type="text" name="original" autocomplete="off" placeholder="请输入原关系" class="layui-input" lay-verify="required"/>
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
                        calculation_type = data.calculation_type,
                        appellation_type = data.appellation_type,
                        sex = data.sex,
                        original = data.original;
                    
                    submit_button.disabled = true;
                    
                    if(!calculation_type || !appellation_type || !sex || !original){
                        layer.close(load);
                        layer.msg('请填写完整信息');
                        submit_button.disabled = false;
                    } else {
                        axios.get('https://api.heroa.cn:3403/convenience/calculation_relative_relation/?calculation_type=' + calculation_type + '&appellation_type=' + appellation_type + '&sex=' + sex + '&original=' + original)
                            .then(function(data) {
                                layer.close(load);
                                layer.alert(data.data.information);
                                submit_button.disabled = false;
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>