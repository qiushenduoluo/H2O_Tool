<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
            
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item" pane="">
                        <label class="layui-form-label">图片</label>
                        <div class="layui-input-block">
                            <div id="image"></div>
                        </div>
                    </div>
                    
                    <div class="layui-form-item" pane="">
                        <label class="layui-form-label">全面漫画化</label>
                        <div class="layui-input-block">
                            <input type="checkbox" checked="" name="comprehensive" lay-skin="switch" lay-filter="switchTest" title="全面漫画化"/>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn layui-btn-normal" id="choice_file">选择文件</button>
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">提交</button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        
        <div id="result" style="display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                <div id="result_image"></div>
            </div>
        </div>
        
        <script type="text/javascript">
            var image = '';
            
            layui.use(['upload', 'form'], function() {
                var upload = layui.upload,
                    form = layui.form;
                
                upload.render({
                    elem: '#choice_file'
                    ,auto: false
                    ,choose: function(obj){
                        obj.preview(function(index, file, result) {
                            image = result;
                            document.getElementById('image').innerHTML = '<img width="50%" src="' + image + '">';
                        });
                    }
                });
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        comprehensive = data.comprehensive,
                        result = document.getElementById('result'),
                        result_image = document.getElementById('result_image');
                    
                    submit_button.disabled = true;
                    result_image.innerHTML = '';
                    result.style.display = 'none';
                    
                    if (comprehensive == 'on') {
                        comprehensive = 'true';
                    } else {
                        comprehensive = 'false';
                    }
                    if(!image || !comprehensive){
                        layer.close(load);
                        layer.msg('请填写完整信息');
                        submit_button.disabled = false;
                    } else {
                        let post_data = {
                            'image': image,
                            'comprehensive': comprehensive
                        };
                        axios.post('https://api.heroa.cn:3403/convenience/image_caricature/', post_data)
                            .then(function(data) {
                                data = data.data.information;
                                if (typeof data == 'object') {
                                    result_image.innerHTML = '<img width="50%" src="' + data + '">';
                                    layer.close(load);
                                    result.style.display = "block";
                                    submit_button.disabled = false;
                                } else {
                                    layer.close(load);
                                    layer.alert('未知错误');
                                    submit_button.disabled = false;
                                }
                        });
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>