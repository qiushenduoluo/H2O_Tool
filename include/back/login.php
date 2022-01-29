<?php
    error_reporting(0);
    header('Content-type: application/json; charset=utf-8');
    
    require_once './core.php';
    
    function get() {
        $data = request_http('https://api.heroa.cn:3403/qq/qr_code_login/?type=get')[1];
        $data = json_decode($data, true);
        $data = $data['information'];
        $qr_code = $data['qr_code'];
        $data_redirect = request_http('http://api.cccyun.cc/api/qrcode_noauth.php', 1, 'image='.urlencode(str_replace('data:image/png;base64,', '', $qr_code)))[1];
        $data_redirect = json_decode($data_redirect, true);
        $redirect_url = 'mqqapi://forward/url?version=1&src_type=web&url_prefix='.base64_encode($data_redirect['url']);
        return [$data['qr_sig'], $qr_code, $redirect_url];
    }
    
    function result($qr_sig) {
        $data = request_http('https://api.heroa.cn:3403/qq/qr_code_login/?type=result&qr_sig='.$qr_sig)[1];
        $data = json_decode($data, true);
        $data = $data['information'];
        if (!is_array($data)) {
            return $data;
        }
        $cookie = $data['cookie'];
        return [$data['base']['qq_number'], $cookie['uin'], $cookie['skey'], $cookie['p_uin'], $cookie['p_skey'], $cookie['pt4_token'], $cookie['skey_g_tk']];
    }
    
    function return_response_result($result) {
        if (is_array($result)) {
            $result = stripslashes(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    	exit($result);
    }
    
    $type = $_GET['type'];
    $qr_sig = $_GET['qr_sig'];
    if (($type != 'get' and $type != 'result') or ($type == 'result' and empty($qr_sig))) {
        return_response_result('参数错误');
    }
    if ($type == 'get') {
        $login_data = get();
        $result = array(
            'qr_sig'=>$login_data[0],
            'qr_code'=>$login_data[1],
            'redirect_url'=>$login_data[2]
        );
        return_response_result($result);
    } else {
        $result_data = result($qr_sig);
        if (is_string($result_data)) {
            return_response_result($result_data);
        }
        $result = array(
            'qq_number' => $result_data[0],
            'uin'=>$result_data[1],
            'skey'=>$result_data[2],
            'p_uin'=>$result_data[3],
            'p_skey'=>$result_data[4],
            'pt4_token'=>$result_data[5],
            'g_tk'=>$result_data[6]
        );
        return_response_result($result);
    }
?>