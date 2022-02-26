                <?php
                    if ($page_name != 'index.php' and $page_name != 'doc.php' and $page_name != 'about.php' and $page_name != 'management.php') {
                        if ($core_data['comment']['open']) {
                            echo '
                                <div class="layui-card">
                                    <div class="layui-card-header">评论</div>
                                    <div class="layui-card-body">
                                        <div id="comment"></div>
                                    </div>
                                </div>
                                <script>
                                    Waline({
                                      el: "#comment",
                                      serverURL: "'.$core_data['comment']['vercel_url'].'",
                                    });
                                </script>
                            ';
                        }
                        echo '
                                </div>
                            </div>
                        ';
                    }
                ?>
            </div>
            <br/>
            <div class="layui-bg-gray" style="padding: 10px;">
                <div class="layui-row layui-col-space10">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            友情链接
                        </div>
                        <div class="layui-card-body">
                            <?php
                                foreach ($link_data as $link_data_key => $link_data_value) {
                                    echo '
                                        <a href="'.$link_data_value.'" target="_blank">'.$link_data_key.'</a>
                                    ';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 引用js -->
        <?php
            if ($verification['open']) {
                echo '
                    <script type="text/javascript">
                        document.write(\'<script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/verification_login.js?timestmap=\' + Math.random() + \'"><\/script>\');
                    </script>
                ';
            }
        ?>
        <script type="text/javascript">
            document.write('<script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/qq_login.js?timestmap=' + Math.random() + '"><\/script>');
            document.write('<script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/permission_login.js?timestmap=' + Math.random() + '"><\/script>');
        </script>
        <!-- js -->
        <script type="text/javascript">
            function nav_active(id) {
                var nav = document.getElementById('nav').children;
                
                document.getElementById(id).className = 'layui-nav-item layui-this';
                Object.keys(nav).forEach(function(key){
                    if (nav[key].id != id) {
                        var single_nav = document.getElementById(nav[key].id);
                        
                        if (single_nav != null) {
                            single_nav.className = 'layui-nav-item';
                        }
                    }
                });
            }
            
            function refresh_nav() {
                var hash = window.location.hash,
                    page_path = window.location.pathname;
                    is_tool = page_path.split('/');
                
                if (is_tool.length == 4) {
                    is_tool = true;
                }
                
                if (page_path == '/' || page_path == '/index.php' || is_tool == true) {
                    nav_active('index_button');
                } else if (page_path == '/doc.php') {
                    nav_active('doc_button');
                } else {
                    nav_active('management_button');
                }
            }
            
            function refresh_quotation() {
                axios.get('https://api.heroa.cn:3403/random/quotation/')
                    .then(function(data) {
                        data = data.data.information;
                        document.getElementById('quotation').innerHTML = data;
                });
            }
            
            $(document).ready(function() {
                refresh_nav();
                refresh_quotation();
            });
            
            <?php
                if ($core_data['open_pjax']) {
                    echo '
                        $(document).pjax("a[target!=_blank]", {
                            container: "#container",
                            fragment: "#container",
                            timeout: 6000
                        }).on("pjax:start",
                            NProgress.start
                        ).on("pjax:success", function() {
                            _hmt.push(["_trackPageview", document.location.pathname]);
                            layer.closeAll();
                            refresh_nav();
                            refresh_quotation();
                            layui.use("form", function() {
                                var element = layui.element
                                ,form = layui.form;
                                
                                element.init();
                                form.render();
                            });
                        }).on("pjax:end",
                            NProgress.done
                        );
                    ';
                }
            ?>
        </script>
    </body>
    
</html>