<?php require_once './include/front/header.php'; ?>
        
        <fieldset class="layui-elem-field layui-field-title">
            <legend>信息</legend>
        </fieldset>
        <div class="layui-bg-gray" style="padding: 10px;">
            <div class="layui-row layui-col-space10">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">公告</div>
                        <div id="notice" class="layui-card-body"></div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">PHP版本</div>
                        <div class="layui-card-body">
                            <?php echo phpversion() ;?>
                        </div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">当前版本</div>
                        <div class="layui-card-body">
                            1.2
                        </div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">最新版本</div>
                        <div id="latest_version" class="layui-card-body"></div>
                    </div>
                </div>
                
                <div class="layui-col-md4">
                    <a id="download_url" href="https://www.heroa.cn/" target="_blank">
                        <button type="button" class="layui-btn">下载</button>
                    </a>
                </div>
            </div>
        </div> 
        
        <fieldset class="layui-elem-field layui-field-title">
            <legend>历史</legend>
        </fieldset>
        <ul id="history" class="layui-timeline"></ul>
        
        <fieldset class="layui-elem-field layui-field-title">
            <legend>问题</legend>
        </fieldset>
        <div class="layui-collapse">
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">程序运行环境要求是什么?</h2>
                <div class="layui-colla-content">
                    <p>
                        1.PHP >= 7.3.<br/>
                        2.支持cURL.
                    </p>
                </div>
            </div>
            
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">如何修改网站基本信息?</h2>
                <div class="layui-colla-content">
                    <p>
                        1.在/data/core.json中修改.<br/>
                        2.如不需要公告,将notice值填写"",即"notice": "".
                    </p>
                </div>
            </div>
            
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">如何增加广告?</h2>
                <div class="layui-colla-content">
                    <p>
                        在/data/advertisement.json中按照里面的格式填写,链接不能相同.
                    </p>
                </div>
            </div>
            
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">如何增加新工具?</h2>
                <div class="layui-colla-content">
                    <p>
                        1.首先，在/data/tool.json中按照里面的格式填写.<br/>
                        2.然后,工具展示可自定义颜色,在单个工具的键值中修改,例如"color": "red".<br/>
                        3.最后,把工具文件放在/tool/分类名/中,工具名要与你json中填写的一致.
                    </p>
                </div>
            </div>
            
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">如何增加友情链接?</h2>
                <div class="layui-colla-content">
                    <p>
                        在/data/link.json中按照里面的格式填写.
                    </p>
                </div>
            </div>
            
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">为什么需要扫码登录?</h2>
                <div class="layui-colla-content">
                    <p>
                        1.部分QQ工具需要Cookie才能进行使用.<br/>
                        2.如担心风险不用即可.
                    </p>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function() {
                axios.get('https://www.heroa.cn/data/about.php')
                    .then(function(data) {
                        var history_label = document.getElementById('history');
                        
                        data = data.data;
                        history_distinguish = data.history;
                        document.getElementById('notice').innerHTML = data.notice;
                        document.getElementById('latest_version').innerHTML = data.latest_version;
                        document.getElementById('download_url').href = data.download_url;
                        for (let history_count in history_distinguish) {
                            history_label.innerHTML += ' \
                                <li class="layui-timeline-item"> \
                                    <i class="layui-icon layui-timeline-axis"></i> \
                                    <div class="layui-timeline-content layui-text"> \
                                        <h3 class="layui-timeline-title">Ver' + history_count + '(' + history_distinguish[history_count].date + ')</h3> \
                                        <p>' + history_distinguish[history_count].event + '</p> \
                                    </div> \
                                </li> \
                            ';
                        }
                });
            });
        </script>
        
<?php require_once './include/front/footer.php'; ?>