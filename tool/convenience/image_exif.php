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
            var image = '';
            
            layui.use(['upload', 'form'], function() {
                var upload = layui.upload,
                    form = layui.form;
                
                upload.render({
                    elem: '#choice_file'
                    ,auto: false
                    ,choose: function(obj) {
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
                        result = document.getElementById('result'),
                        result_image = document.getElementById('result_image');
                    
                    submit_button.disabled = true;
                    $('tr:gt(0)').remove();
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
                    
                    if(!image){
                        layer.close(load);
                        layer.msg('请填写完整信息');
                        submit_button.disabled = false;
                    } else {
                        let post_data = {
                            'image': image
                        };
                        axios.post('https://api.heroa.cn:3403/convenience/image_exif/', post_data)
                            .then(function(data) {
                                data = data.data.information;
                                if (typeof data == 'object') {
                                    size = data.size;
                                    gps = data.gps;
                                    result_table.innerHTML += ' \
                                        <tr><td>照相机型号</td><td>' + data.camera + '</td></tr> \
                                        <tr><td>长度</td><td>' + size.length + '</td></tr> \
                                        <tr><td>宽度</td><td>' + size.width + '</td></tr> \
                                        <tr><td>经度</td><td>' + gps.longitude + '</td></tr> \
                                        <tr><td>纬度</td><td>' + gps.latitude + '</td></tr> \
                                        <tr><td>时间</td><td>' + data.time + '</td></tr> \
                                    ';
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