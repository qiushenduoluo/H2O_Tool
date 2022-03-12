<?php
    require_once './modular.php';
    
    function return_response_result($result) {
        if (is_array($result)) {
            $result = stripslashes(json_encode($result, JSON_UNESCAPED_UNICODE));
        }
    	exit($result);
    }
    
    function result($id) {
        $data = request_http('https://apibb.rjk66.cn/hzqrlogin/?id='.$id)[1];
        $data = json_decode($data, true);
        if ($data['qrstate'] == 4) {
            $result = array(
                'state'=>'已登录',
                'qq_number'=>$data['uin']
            );
            return_response_result($result);
        } else if ($data['msg'] == '系统错误，请稍后重试或联系管理员。') {
            return_response_result('已失效');
        }
        return_response_result($data['msg']);
    }
    
    $type = $_GET['type'];
    $id = $_GET['id'];
    if ($type != 'result' or empty($id)) {
        return_response_result('参数错误');
    }
    return_response_result(result($id));
?>