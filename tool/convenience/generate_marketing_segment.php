<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">主题</label>
                        <div class="layui-input-block">
                            <input type="text" name="subject" autocomplete="off" placeholder="请输入主题" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">事件1</label>
                        <div class="layui-input-block">
                            <input type="text" name="event_1" autocomplete="off" placeholder="请输入事件1" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">事件2</label>
                        <div class="layui-input-block">
                            <input type="text" name="event_2" autocomplete="off" placeholder="请输入事件2" class="layui-input" lay-verify="required"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">生成</button>
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
                        subject = data.subject,
                        event_1 = data.event_1,
                        event_2 = data.event_2,
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
                    
                    if(!subject || !event_1 || !event_2) {
                        layer.close(load);
                        layer.msg('请输入完整信息');
                        submit_button.disabled = false;
                    } else {
                        result_text.innerHTML = `${subject}${event_1}是怎么回事呢？${subject}相信大家都很熟悉，但是${subject}${event_1}是怎么回事呢，下面就让小编带大家一起了解吧。<br/>${subject}${event_1}，其实就是${event_2}，大家可能会很惊讶${subject}怎么会${event_1}呢？但事实就是这样，小编也感到非常惊讶。<br/>这就是关于${subject}${event_1}的事情了，大家有什么想法呢，欢迎在评论区告诉小编一起讨论哦！`;
                        layer.close(load);
                        result.style.display = "block";
                        submit_button.disabled = false;
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>