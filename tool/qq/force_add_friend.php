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
        
        <script type="text/javascript">
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        qq_number = data.qq_number;
                    
                    submit_button.disabled = true;
                    if(!qq_number){
                        layer.close(load);
                        layer.msg('请输入QQ号');
                        submit_button.disabled = false;
                    } else {
                        layer.close(load);
                        submit_button.disabled = false;
                        window.location.href = 'https://api.heroa.cn:3403/qq/add_friend/?qq_number=' + qq_number;
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>