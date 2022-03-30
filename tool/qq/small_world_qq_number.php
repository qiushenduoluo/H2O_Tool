<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.进入小世界个人主页.<br/>
                2.点击右上角的三个点.<br/>
                3.点击举报.<br/>
                4.再点击右上角三个点复制链接.<br/>
                5.粘贴至本页面输入框.<br/>
                6.点击查询即可.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">链接</label>
                        <div class="layui-input-block">
                            <input type="text" name="url" autocomplete="off" placeholder="请输入链接" class="layui-input" lay-verify="required"/>
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
                        url = data.url;
                    
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
                    
                    if(!url){
                        layer.close(load);
                        layer.msg('请输入链接');
                        submit_button.disabled = false;
                    } else {
                        var qq_number = get_middle_text(url, 'eviluin=', '&appname');
                        
                        layer.close(load);
                        layer.open({
                            content: 'QQ账号:' + qq_number
                            ,btn: ['加为好友', '复制', '取消']
                            ,yes: function(index, layero){
                                window.location.href = 'https://api.heroa.cn:3403/qq/add_friend/?qq_number=' + qq_number;
                            }
                            ,btn2: function(index, layero){
                                copy_text(qq_number);
                                layer.msg('复制成功');
                            }
                        });
                        submit_button.disabled = false;
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>