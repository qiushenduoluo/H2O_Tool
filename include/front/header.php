<?php
    $page_name = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1);
    
    if ($page_name == 'index.php' or $page_name == 'doc.php' or $page_name == 'management.php') {
        define('ROOT_PATH', './');
    } else {
        define('ROOT_PATH', '../../');
    }
    
    require_once ROOT_PATH.'include/back/core.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $keyword; ?>"/>
        <meta name="description" content="<?php echo $core_data['description']; ?>"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $core_data['ico_url']; ?>"/>
        <!-- 引用css -->
        <link rel="stylesheet" type="text/css" href="<?php echo ROOT_PATH; ?>include/front/layui/css/layui.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo ROOT_PATH; ?>include/front/css/popup.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo ROOT_PATH; ?>include/front/css/nprogress.css"/>
        <!-- 引用js -->
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/layui/layui.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/jquery.pjax.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/sweetalert.min.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/nprogress.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/axios.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/popup.js"></script>
        <script type="text/javascript">
            document.write('<script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/modular.js?timestmap=' + Math.random() + '"><\/script>');
        </script>
        <script type="text/javascript" src="<?php echo ROOT_PATH; ?>include/front/js/waline.js"></script>
        <!-- layui-js -->
        <script type="text/javascript">
            layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'util'], function() {
                var layer = layui.layer
                ,util = layui.util;
                
                util.fixbar({
                    bar1: '&#xe676'
                    ,bar2: false
                    ,css: {right: 30, bottom: 100}
                    ,bgcolor: '#9F9F9F'
                    ,click: function(type) {
                        if(type === 'bar1') {
                            window.open('<?php echo $core_data["qq_group_url"]; ?>');
                        }
                    }
                });
            });
        </script>
        <!-- sweetalert-js -->
        <script type="text/javascript">
            if (Date.parse(new Date()) - get_cookie('notice_timestamp') > 24 * 60 * 60 * 1000) {
                <?php
                    $notice = $core_data['notice'];
                    if ($notice['open'] and $page_name != 'management.php') {
                        echo '
                            $(document).ready(function() {
                                swal("'.$notice['content'].'", {
                                    title: "公告",
                                    buttons: {
                                        catch: {
                                            text: "'.$notice['button_name'].'",
                                            value: "button_event",
                                        },
                                        cancel: "取消"
                                    },
                                })
                                .then((value) => {
                                  switch (value) {
                                    case "button_event":
                                        window.open("'.$notice['button_url'].'");
                                        break;
                                    }
                                });
                                set_cookie("notice_timestamp", Date.parse(new Date()))
                            })
                        ';
                    }
                ?>
            }
        </script>
        <!-- 自定义CSS -->
        <style type="text/css">
            <?php echo base64_decode($core_data['custom_code']['css']); ?>
        </style>
        <!-- 自定义JavaScript -->
        <script type="text/javascript">
            <?php echo base64_decode($core_data['custom_code']['javascript']); ?>
        </script>
    </head>
    
    <body>
        <ul id="nav" class="layui-nav" lay-filter="navigation">
            <li id="index_button" class="layui-nav-item"><a href="/">首页</a></li>
            <li id="user_button" class="layui-nav-item">
                <a href="javascript:void(0);">登录</a>
                <dl class="layui-nav-child">
                    <?php
                        if ($verification['open']) {
                            echo '
                                <dd><a id="verification_state"></a></dd>
                                <dd><a href="javascript:void(0);" onclick="verification_login()">验证登录</a></dd>
                                <dd><a href="javascript:void(0);" onclick="verification_log_out()">验证退出</a></dd>
                                <hr/>
                            ';
                        }
                    ?>
                    <dd><a id="qq_state"></a></dd>
                    <dd><a id="qq_number"></a></dd>
                    <dd><a href="javascript:void(0);" onclick="qq_login()">QQ登录</a></dd>
                    <dd><a href="javascript:void(0);" onclick="qq_log_out()">QQ退出</a></dd>
                    <hr/>
                    <dd><a id="permission_state"></a></dd>
                    <dd><a href="javascript:void(0);" onclick="permission_login()">权限登录</a></dd>
                    <dd><a href="javascript:void(0);" onclick="permission_log_out()">权限退出</a></dd>
                </dl>
            </li>
            <li id="doc_button" class="layui-nav-item"><a href="/doc.php">文档</a></li>
            <li id="management_button" class="layui-nav-item"><a href="/management.php">管理</a></li>
        </ul>
        
        <div style="padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;">
            <br/>
            <blockquote id="quotation" class="layui-elem-quote"></blockquote>
            <div id="container">
                <?php
                    if ($page_name != 'index.php' and $page_name != 'doc.php' and $page_name != 'management.php') {
                        echo '
                            <div class="layui-bg-gray" style="margin-top: 15px; padding: 10px;">
                                <div class="layui-row layui-col-space10">
                        ';
                    }
                ?>