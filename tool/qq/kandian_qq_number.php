<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.进入看点个人主页复制链接.<br/>
                2.粘贴至本页面输入框.<br/>
                3.点击查询即可.<br/>
                4.如显示用户不存在或重新获取sec_uin,请重新进入个人主页复制(每个人的链接不是固定的).<br/>
                5.如显示为13位的账号是属于腾讯内容开放平台注册的,无法查询到账号.<br/>
                6.有些QQ关闭了搜索,直接在QQ内搜索是不行的,查询成功后会有"加为好友"的按钮.
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
                    
                    if(!url){
                        layer.close(load);
                        layer.msg('请输入链接');
                        submit_button.disabled = false;
                    } else {
                        var sec_uin = '';
                        
                        if (url.indexOf('secUin') != -1 ) {
                          sec_uin = get_middle_text(url, 'secUin=', '&iid');
                        } else {
                          sec_uin = url;
                        }
                        axios.get('https://api.heroa.cn:3403/qq/kandian_qq_number/?sec_uin=' + sec_uin)
                            .then(function(data) {
                                layer.close(load);
                                data = data.data.information;
                                if (data % 1 == 0 && (data >= 10000 && data <= 9999999999)) {
                                    layer.open({
                                        content: 'QQ账号:' + data
                                        ,btn: ['加为好友', '复制', '取消']
                                        ,yes: function(index, layero){
                                            window.location.href = 'https://api.heroa.cn:3403/qq/add_friend/?qq_number=' + data;
                                        }
                                        ,btn2: function(index, layero){
                                            copy_text(data);
                                            layer.msg('复制成功');
                                        }
                                    });
                                } else {
                                  layer.open({
                                    content: data
                                  });
                                }
                                submit_button.disabled = false;
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>