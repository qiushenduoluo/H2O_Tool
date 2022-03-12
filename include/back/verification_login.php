<?php
    require_once './modular.php';
    
    $skey = $_GET['skey'];
    $p_uin= $_GET['p_uin'];
    $p_skey = $_GET['p_skey'];
    if (empty($skey) or empty($p_uin) or empty($p_skey)) {
        exit('参数错误');
    }
    $data = request_http('https://api.heroa.cn:3403/qq/group_list/?skey='.$skey.'&p_uin='.$p_uin.'&p_skey='.$p_skey)[1];
    $data = json_decode($data, true);
    $data = $data['information'];
    $core_data = read('core');
    $verification = $core_data['verification'];
    if (empty(array_intersect($verification['qq_group_number'],array_keys($data)))) {
        exit($core_data['qq_group_url']);
    }
    exit('验证成功');
?>