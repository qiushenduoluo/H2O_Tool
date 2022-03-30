<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-block">
                            <select name="type" lay-verify="required">
                                <option value="generate" selected="">生成</option>
                                <option value="reduction">还原</option>
                          </select>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <label class="layui-form-label">文本</label>
                        <div class="layui-input-block">
                            <input type="text" name="text" autocomplete="off" placeholder="请输入文本" class="layui-input" lay-verify="required"/>
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
                <p id="result_text"></p>
            </div>
        </div>
        
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/grapheme-splitter.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/abstract_word.js"></script>
        <script type="text/javascript">
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        type = data.type,
                        text = data.text,
                        result = document.getElementById('result'),
                        result_text = document.getElementById('result_text');
                    
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
                    
                    if(!type || !text){
                        layer.close(load);
                        layer.msg('请填写完整信息');
                        submit_button.disabled = false;
                    } else {
                        if (type == 'generate') {
                            information = chouxiang(text);
                        } else {
                            information = dechouxiang(text);
                        }
                        result_text.innerHTML = information;
                        layer.close(load);
                        result.style.display = "block";
                        submit_button.disabled = false;
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>