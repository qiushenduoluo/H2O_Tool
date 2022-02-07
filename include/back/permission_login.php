<?php
    require_once './modular.php';
    
    function get() {
        $data = request_http('http://qr.rjk66.cn/api/gethzqr/')[1];
        $data = json_decode($data, true);
        $data = $data['data'];
        $qr_code = $data['qrcode'];
        $data_redirect = request_http('http://api.cccyun.cc/api/qrcode_noauth.php', 1, 'image='.urlencode(str_replace('data:image/png;base64,', '', $qr_code)))[1];
        $data_redirect = json_decode($data_redirect, true);
        $redirect_url = 'mqqapi://forward/url?version=1&src_type=web&url_prefix='.base64_encode($data_redirect['url']);
        return [$data['id'], $qr_code, $redirect_url];
    }
    
    function result($id) {
        $data = request_http('https://apibb.rjk66.cn/hzqrlogin/?id='.$id)[1];
        $data = json_decode($data, true);
        if ($data['qrstate'] == 4) {
            return_response_result('已登录');
        } else if ($data['msg'] == '系统错误，请稍后重试或联系管理员。') {
            return_response_result('已失效');
        }
        return_response_result($data['msg']);
    }
    
    function return_response_result($result) {
        if (is_array($result)) {
            $result = stripslashes(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    	exit($result);
    }
    
    $type = $_GET['type'];
    $id = $_GET['id'];
    if (($type != 'get' and $type != 'result') or ($type == 'result' and empty($id))) {
        return_response_result('参数错误');
    }
    if ($type == 'get') {
        $login_data = get();
        $result = array(
            'id'=>$login_data[0],
            'qr_code'=>$login_data[1],
            'redirect_url'=>$login_data[2]
        );
        return_response_result($result);
    } else {
        return_response_result(result($id));
    }
?>