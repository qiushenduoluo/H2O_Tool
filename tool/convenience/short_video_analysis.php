<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">说明</div>
            <div class="layui-card-body">
                1.仅支持抖音和快手.<br>
                2.图片暂时不能解析.
            </div>
        </div>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">作品网址</label>
                        <div class="layui-input-block">
                            <textarea type="text" name="url" placeholder="请输入作品网址(一行一个)" class="layui-textarea" lay-verify="required"></textarea>
                        </div>
                    </div>
                    
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">解析</button>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        
        <div id="result" style="overflow: auto; display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                <table class="layui-hide" id="result_table"></table>
            </div>
        </div>
        
        <script type="text/javascript">
            layui.use(['form'], function() {
                var form = layui.form;
                
                form.on('submit(submit_button)', function(data) {
                    data = data.field;
                    
                    var load = layer.load(0, {shade: false}),
                        submit_button = document.getElementById('submit_button'),
                        url = data.url,
                        result = document.getElementById('result');
                    
                    submit_button.disabled = true;
                    result.style.display = 'none';
                    
                    if(!url){
                        layer.close(load);
                        layer.msg('请输入作品网址');
                        submit_button.disabled = false;
                    } else {
                        var analysis_data = [];
                        
                        url = url.split('\n');
                        for (const url_count in url) {
                            var single_url = url[url_count],
                                type = '';
                            
                            single_url = get_url(single_url)[0];
                            if (single_url.indexOf('douyin') != -1) {
                                type = 'tiktok';
                            } else if (single_url.indexOf('kuaishou') != -1) {
                                type = 'kwai_fu';
                            } else {
                                layui.use('table', function() {
                                    var table = layui.table;
                                    
                                    table.render({
                                        elem: '#result_table'
                                        ,cols: [[
                                            {field: 'description', title: '描述', width: 200}
                                            ,{field: 'cover_url', title: '封面网址', width: 300}
                                            ,{field: 'video_url', title: '视频网址', width: 250}
                                        ]]
                                        ,data: [
                                            {
                                                'description': '作品网址错误',
                                                'cover_url': '作品网址错误',
                                                'video_url': '作品网址错误'
                                            }
                                        ]
                                    });
                                });
                                
                                continue;
                            }
                            axios.get('https://api.heroa.cn:3403/convenience/short_video_analysis/?type=' + type + '&url=' + single_url)
                                .then(function(data) {
                                    data = data.data.information;
                                    if (data == '未知错误') {
                                        analysis_data.push({
                                            'description': '作品网址错误',
                                            'cover_url': '作品网址错误',
                                            'video_url': '作品网址错误'
                                        });
                                    } else {
                                        analysis_data.push({
                                            'description': data.description,
                                            'cover_url': data.cover_url,
                                            'video_url': data.video_url
                                        });
                                    }
                                    layui.use('table', function() {
                                        var table = layui.table;
                                        
                                        table.render({
                                            elem: '#result_table'
                                            ,cols: [[
                                                {field: 'description', title: '描述', width: 550}
                                                ,{field: 'cover_url', title: '封面网址', width: 300, templet: '<div><a href="{{ d.cover_url }}" target="_blank">{{ d.cover_url }}</a></div>'}
                                                ,{field: 'video_url', title: '视频网址', width: 300}
                                            ]]
                                            ,data: analysis_data
                                        });
                                    });
                            });
                        }
                        layer.close(load);
                        result.style.display = "block";
                        submit_button.disabled = false;
                    }
                    
                    return false;
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>