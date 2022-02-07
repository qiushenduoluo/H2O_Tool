<?php require_once './include/front/header.php'; ?>
        
        <?php
            if (!$_SESSION['login']) {
                echo '
                    <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                        <div class="layui-row layui-col-space10">
                            <div class="layui-card">
                                <div class="layui-card-header">控制台</div>
                                <div class="layui-card-body">
                                    
                                    <form class="layui-form layui-form-pane">
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">密码</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="password" autocomplete="off" placeholder="请输入密码" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                <button type="submit" id="submit_button" class="layui-btn" lay-submit="" lay-filter="submit_button">登录</button>
                                            </div>
                                        </div>
                                        
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
                        layui.use(["form"], function() {
                            var form = layui.form;
                            
                            form.on("submit(submit_button)", function(data) {
                                data = data.field;
                                
                                var load = layer.load(0, {shade: false}),
                                    submit_button = document.getElementById("submit_button"),
                                    password = data.password;
                                
                                submit_button.disabled = true;
                                
                                if(!password){
                                    layer.close(load);
                                    layer.msg("参数错误");
                                    submit_button.disabled = false;
                                } else {
                                    let post_data = {
                                        "action": "login",
                                        "value": password
                                    };
                                    
                                    axios.post("./include/back/management.php", post_data)
                                        .then(function(data) {
                                            data = data.data;
                                            layer.close(load);
                                            if (data == "登录成功") {
                                                location.reload();
                                            } else {
                                                layer.msg("密码错误");
                                            }
                                            submit_button.disabled = false;
                                    });
                                }
                                
                                return false;
                            });
                        });
                    </script>
                ';
                require_once './include/front/footer.php';
                exit();
            }
        ?>
        
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                <li class="layui-this">总揽</li>
                <li>广告</li>
                <li>友链</li>
                <li>核心</li>
                <li>密码</li>
            </ul>
            
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>信息</legend>
                    </fieldset>
                    <div class="layui-bg-gray" style="padding: 5px;">
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
                                        1.5
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
                                <div class="layui-card">
                                    <div class="layui-card-header">分类数</div>
                                    <div class="layui-card-body">
                                        <?php echo count($tool_data); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-col-md4">
                                <div class="layui-card">
                                    <div class="layui-card-header">工具数</div>
                                    <div class="layui-card-body">
                                        <?php
                                            $tool_count = 0;
                                            foreach ($tool_data as $tool_data_key => $tool_data_value) {
                                                foreach ($tool_data_value as $tool_data_value_key => $tool_data_value_value) {
                                                    if ($tool_data_value_key != 'name') {
                                                        $tool_count += 1;
                                                    }
                                                }
                                            }
                                            echo $tool_count;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-col-md4">
                                <div class="layui-card">
                                    <div class="layui-card-header">工具使用总次数</div>
                                    <div class="layui-card-body">
                                        <?php
                                            $tool_times_count = 0;
                                            foreach ($tool_data as $tool_data_key => $tool_data_value) {
                                                foreach ($tool_data_value as $tool_data_value_key => $tool_data_value_value) {
                                                    if ($tool_data_value_key != 'name') {
                                                        $tool_times_count += $tool_times_data[$tool_data_key][$tool_data_value_key];
                                                    }
                                                }
                                            }
                                            echo $tool_times_count;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-col-md4">
                                <div class="layui-card">
                                    <div class="layui-card-header">广告数</div>
                                    <div class="layui-card-body">
                                        <?php echo count($advertisement_data); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-col-md4">
                                <div class="layui-card">
                                    <div class="layui-card-header">友链数</div>
                                    <div class="layui-card-body">
                                        <?php echo count($link_data); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="layui-col-md4">
                                <a id="download_url" href="https://www.heroa.cn/" target="_blank">
                                    <button type="button" class="layui-btn">下载</button>
                                </a>
                                
                                <a id="download_url" href="https://github.com/XiaoXinYo/H2O_Tool" target="_blank">
                                    <button type="button" class="layui-btn">GitHub</button>
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
                            <h2 class="layui-colla-title">如何增加新工具?</h2>
                            <div class="layui-colla-content">
                                <p>
                                    1.首先,在/data/tool.json中按照里面的格式填写.<br>
                                    2.其次,工具可自定义网址,在单个工具的键值中修改,例如"url": "https://www.baidu.com/",网址只能指向外站.<br>
                                    3.再次,如工具需在热门中展示,在单个工具的键值中修改,增加"hot": "true".<br>
                                    4.然后,工具展示可自定义颜色,在单个工具的键值中修改,例如"color": "red".<br>
                                    5.最后,把工具文件放在/tool/分类名/中,工具名要与你json中填写的一致.
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
                        
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">为什么扫码登录失败?</h2>
                            <div class="layui-colla-content">
                                <p>
                                    1.部分手机QQ用户无法从相册选中图片进行扫描,会直接拒绝登陆(无法识别二维码).<br/>
                                    2.解决方案①:使用TIM从相册选中图片进行扫描.<br/>
                                    解决方案②:使用电脑或另一台手机打开页面,然后扫描.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-tab-item">
                    <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                        <div class="layui-row layui-col-space10">
                            <div class="layui-card">
                                <div class="layui-card-header">控制台</div>
                                <div class="layui-card-body">
                                    <table class="layui-hide" id="advertisement_table" lay-filter="advertisement_table"></table>
                                    <script type="text/html" id="advertisement_tool_bar">
                                        <div class="layui-btn-container">
                                            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                                        </div>
                                    </script>
                                    <script type="text/html" id="advertisement_bar">
                                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-tab-item">
                    <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                        <div class="layui-row layui-col-space10">
                            <div class="layui-card">
                                <div class="layui-card-header">控制台</div>
                                <div class="layui-card-body">
                                    <table class="layui-hide" id="link_table" lay-filter="link_table"></table>
                                    <script type="text/html" id="link_tool_bar">
                                        <div class="layui-btn-container">
                                            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                                        </div>
                                    </script>
                                    <script type="text/html" id="link_bar">
                                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-tab-item">
                    <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                        <div class="layui-row layui-col-space10">
                            
                            <div class="layui-card">
                                <div class="layui-card-header">说明</div>
                                <div class="layui-card-body">
                                    多个QQ群号用英文,隔开.
                                </div>
                            </div>
                            
                            <div class="layui-card">
                                <div class="layui-card-header">控制台</div>
                                <div class="layui-card-body">
                                    
                                    <form class="layui-form layui-form-pane">
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="title" value="<?php echo $core_data['title']; ?>" autocomplete="off" placeholder="请输入标题" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">副标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="subtitle" value="<?php echo $core_data['subtitle']; ?>" autocomplete="off" placeholder="请输入副标题" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">关键词</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="keyword" value="<?php echo $core_data['keyword']; ?>" autocomplete="off" placeholder="请输入关键词" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">描述</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="description" value="<?php echo $core_data['description']; ?>" autocomplete="off" placeholder="请输入描述" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">图标网址</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="ico_url" value="<?php echo $core_data['ico_url']; ?>" autocomplete="off" placeholder="请输入图标网址" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">公告时间</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="notice_time" value="<?php echo $core_data['notice']['time']; ?>" autocomplete="off" placeholder="请输入公告时间" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">公告内容</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="notice_content" value="<?php echo $core_data['notice']['content']; ?>" autocomplete="off" placeholder="请输入公告内容" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">公告按钮名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="notice_button_name" value="<?php echo $core_data['notice']['button_name']; ?>" autocomplete="off" placeholder="请输入公告按钮名称" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">公告按钮链接</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="notice_button_url" value="<?php echo $core_data['notice']['button_url']; ?>" autocomplete="off" placeholder="请输入公告按钮链接" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">QQ群链接</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="qq_group_url" value="<?php echo $core_data['qq_group_url']; ?>" autocomplete="off" placeholder="请输入QQ群链接" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">QQ群号</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="qq_group_number" value="<?php echo implode(',',$core_data['verification']['qq_group_number']); ?>" autocomplete="off" placeholder="请输入QQ群号" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item" pane="">
                                            <label class="layui-form-label">公告</label>
                                            <div class="layui-input-block">
                                                <input type="checkbox" <?php if ($core_data['notice']['open'])echo 'checked=""'; ?> name="notice_open" lay-skin="switch" lay-filter="switchTest" title="公告"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item" pane="">
                                            <label class="layui-form-label">验证</label>
                                            <div class="layui-input-block">
                                                <input type="checkbox" <?php if ($core_data['verification']['open'])echo 'checked=""'; ?> name="verification_open" lay-skin="switch" lay-filter="switchTest" title="QQ群验证"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                <button type="submit" id="core_submit_button" class="layui-btn" lay-submit="" lay-filter="core_submit_button">修改</button>
                                            </div>
                                        </div>
                                        
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="layui-tab-item">
                    <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                        <div class="layui-row layui-col-space10">
                            <div class="layui-card">
                                <div class="layui-card-header">控制台</div>
                                <div class="layui-card-body">
                                    
                                    <form class="layui-form layui-form-pane">
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">新密码</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="new_password" autocomplete="off" placeholder="请输入新密码" class="layui-input" lay-verify="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                           <div class="layui-input-block">
                                               <button type="submit" id="password_submit_button" class="layui-btn" lay-submit="" lay-filter="password_submit_button">修改</button>
                                           </div>
                                        </div>
                                        
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
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
                
                var advertisement_table = null,
                    advertisement_data = [],
                    link_table = null,
                    link_data = [];
                
                axios.get('./data/advertisement.json')
                    .then(function(data) {
                        data = data.data;
                        for (let data_count in data) {
                            advertisement_data.push({
                                'url': data_count,
                                'image_url': data[data_count]
                            });
                        }
                        
                        layui.use('table', function() {
                            var table = layui.table;
                            
                            advertisement_table = table.render({
                                elem: '#advertisement_table'
                                ,cols: [[
                                    {field: 'url', title: '网址', width: 560}
                                    ,{field: 'image_url', title: '图片网址', width: 560},
                                    ,{fixed: 'right', title: '操作', toolbar: '#advertisement_bar', width: 100}
                                ]]
                                ,toolbar: '#advertisement_tool_bar'
                                ,data: advertisement_data
                                ,escape: true
                            });
                        });
                });
                
                axios.get('./data/link.json')
                    .then(function(data) {
                        data = data.data;
                        for (let data_count in data) {
                            link_data.push({
                                'name': data_count,
                                'url': data[data_count]
                            });
                        }
                        
                        layui.use('table', function() {
                            var table = layui.table;
                            
                            link_table = table.render({
                                elem: '#link_table'
                                ,cols: [[
                                    {field: 'name', title: '名称', width: 560}
                                    ,{field: 'url', title: '网址', width: 560},
                                    ,{fixed: 'right', title: '操作', toolbar: '#link_bar', width: 100}
                                ]]
                                ,toolbar: '#link_tool_bar'
                                ,data: link_data
                                ,escape: true
                            });
                        });
                });
                
                layui.use('table', function() {
                    var table = layui.table;
                    
                    table.on('tool(advertisement_table)', function(obj) {
                        if (obj.event == 'delete') {
                            var load = layer.load(0, {shade: false});
                            let post_data = {
                                'action': 'delete_advertisement',
                                'value': obj.data['url']
                            };
                            
                            axios.post("./include/back/management.php", post_data)
                                .then(function(data) {
                                    data = data.data;
                                    if (data == '删除成功') {
                                        obj.del();
                                    }
                                    layer.close(load);
                                    layer.msg(data);
                            });
                        }
                    });
                    
                    table.on('toolbar(advertisement_table)', function(obj) {
                        if (obj.event == 'add') {
                            layer.prompt({
                                formType: 0
                                ,title: '输入框(网址)'
                            }, function (value, index) {
                                var url = value + '?timestamp=' + Date.parse(new Date());
                                
                                layer.close(index);
                                layer.prompt({
                                    formType: 0
                                    ,title: '输入框(图片网址)'
                                }, function (value, index) {
                                    var image_url = value,
                                        load = layer.load(0, {shade: false});
                                    let post_data = {
                                        'action': 'add_advertisement',
                                        'value': {
                                            'url': url,
                                            'image_url': image_url
                                        }
                                    };
                                    layer.close(index);
                                    axios.post('./include/back/management.php', post_data)
                                        .then(function(data) {
                                            data = data.data;
                                            if (data == '添加成功') {
                                                advertisement_data.push({
                                                    'url': url,
                                                    'image_url': image_url
                                                });
                                                advertisement_table.reload({
                                                    data: advertisement_data,
                                                });
                                            }
                                            layer.close(load);
                                            layer.msg(data);
                                    });
                                });
                            });
                        }
                    });
                    
                    table.on('tool(link_table)', function(obj) {
                        if (obj.event == 'delete') {
                            var load = layer.load(0, {shade: false});
                            let post_data = {
                                'action': 'delete_link',
                                'value': obj.data['name']
                            };
                            
                            axios.post("./include/back/management.php", post_data)
                                .then(function(data) {
                                    data = data.data;
                                    if (data == '删除成功') {
                                        obj.del();
                                    }
                                    layer.close(load);
                                    layer.msg(data);
                            });
                        }
                    });
                    
                    table.on('toolbar(link_table)', function(obj) {
                        if (obj.event == 'add') {
                            layer.prompt({
                                formType: 0
                                ,title: '输入框(名称)'
                            }, function (value, index) {
                                var name = value;
                                
                                layer.close(index);
                                layer.prompt({
                                    formType: 0
                                    ,title: '输入框(网址)'
                                }, function (value, index) {
                                    var url = value,
                                        load = layer.load(0, {shade: false});
                                    let post_data = {
                                        'action': 'add_link',
                                        'value': {
                                            'name': name,
                                            'url': url
                                        }
                                    };
                                    layer.close(index);
                                    axios.post('./include/back/management.php', post_data)
                                        .then(function(data) {
                                            data = data.data;
                                            if (data == '添加成功') {
                                                link_data.push({
                                                    'name': name,
                                                    'url': url
                                                });
                                                link_table.reload({
                                                    data: link_data,
                                                });
                                            }
                                            layer.close(load);
                                            layer.msg(data);
                                    });
                                });
                            });
                        }
                    });
                });
                
                layui.use(['form'], function() {
                    var form = layui.form;
                        
                    form.on('submit(core_submit_button)', function(data) {
                        data = data.field;
                        
                        var load = layer.load(0, {shade: false}),
                            submit_button = document.getElementById('core_submit_button'),
                            title = data.title,
                            subtitle = data.subtitle,
                            keyword = data.keyword,
                            description = data.description,
                            ico_url = data.ico_url,
                            notice_time = data.notice_time,
                            notice_content = data.notice_content,
                            notice_button_name = data.notice_button_name,
                            notice_button_url = data.notice_button_url,
                            qq_group_url = data.qq_group_url,
                            qq_group_number = data.qq_group_number,
                            notice_open = data.notice_open,
                            verification_open = data.verification_open;
                        
                        submit_button.disabled = true;
                        
                        if (notice_open == 'on') {
                            notice_open = true;
                        } else {
                            notice_open = false;
                        }
                        if (verification_open == 'on') {
                            verification_open = true;
                        } else {
                            verification_open = false;
                        }
                        if(!title || !subtitle || !keyword || !description || !ico_url || !notice_time || !notice_content || !notice_button_name || !notice_button_url || !qq_group_url || !qq_group_number){
                            layer.close(load);
                            layer.msg('参数错误');
                            submit_button.disabled = false;
                        } else {
                            let post_data = {
                                'action': 'revise_core',
                                'value': {
                                    'title': title,
                                    'subtitle': subtitle,
                                    'keyword': keyword,
                                    'description': description,
                                    'ico_url': ico_url,
                                    'notice_time': notice_time,
                                    'notice_content': notice_content,
                                    'notice_button_name': notice_button_name,
                                    'notice_button_url': notice_button_url,
                                    'qq_group_url': qq_group_url,
                                    'qq_group_number': qq_group_number,
                                    'notice_open': notice_open,
                                    'verification_open': verification_open
                                }
                            };
                            
                            axios.post('./include/back/management.php', post_data)
                                .then(function(data) {
                                    data = data.data;
                                    layer.close(load);
                                    layer.msg(data);
                                    submit_button.disabled = false;
                            });
                        }
                        
                        return false;
                    });
                    
                    form.on('submit(password_submit_button)', function(data) {
                        data = data.field;
                        
                        var load = layer.load(0, {shade: false}),
                            submit_button = document.getElementById('password_submit_button'),
                            new_password = data.new_password;
                        
                        submit_button.disabled = true;
                        
                        if(!new_password){
                            layer.close(load);
                            layer.msg('参数错误');
                            submit_button.disabled = false;
                        } else {
                            let post_data = {
                                'action': 'revise_password',
                                'value': new_password
                            };
                            
                            axios.post('./include/back/management.php', post_data)
                                .then(function(data) {
                                    data = data.data;
                                    layer.close(load);
                                    submit_button.disabled = false;
                                    location.reload();
                            });
                        }
                        
                        return false;
                    });
                    
                });
            });
        </script>
        
<?php require_once './include/front/footer.php'; ?>