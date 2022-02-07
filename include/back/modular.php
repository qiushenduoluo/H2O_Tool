<?php
    error_reporting(0);
    
    define('ROOT_PATH', '../../');
    
    session_start();
    	
    function request_http($url, $type=0, $post_data='', $ua='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.58', $cookie='', $header=array(), $redirect=true){
    	// 初始化curl
    	$curl = curl_init();
    	// 设置网址
    	curl_setopt($curl,CURLOPT_URL, $url);
    	// 设置Ua
    	if (!empty($ua)) {
    		$header[] = 'User-Agent:'.$ua;
    	}
    	// 设置Cookie
    	if (!empty($cookie)) {
    		$header[] = 'Cookie:'.$cookie;
    	}
    	// 设置请求头
    	if (!empty($ua) or !empty($cookie) or !empty($header)) {
    		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    	}
    	// 设置Post数据
    	if($type == 1){
    	    curl_setopt($curl, CURLOPT_POST, true);
    	    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    	}
    	// 设置重定向
    	if (!$redirect) {
    		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
    	}
    	// 过SSL验证证书
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    	// 将头部作为数据流输出
    	curl_setopt($curl, CURLOPT_HEADER, true);
    	// 设置以变量形式存储返回数据
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	// 请求并存储数据
    	$return = curl_exec($curl);
    	// 分割头部和身体
    	if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
    		$return_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    	    $return_header = substr($return, 0, $return_header_size);
    	    $return_data = substr($return, $return_header_size);
    	}
    	// 关闭cURL
    	curl_close($curl);
    	// 返回数据
    	return [$return_header, $return_data];
    }
    
    function read($name) {
        $data = file_get_contents(ROOT_PATH.'data/'.$name.'.json');
        $data = json_decode($data, true);
        return $data;
    }
    
    function write($name, $data) {
        $file = fopen(ROOT_PATH.'data/'.$name.'.json', 'w');
        fwrite($file, json_encode($data, JSON_UNESCAPED_UNICODE));
        fclose($file);
    }
?>