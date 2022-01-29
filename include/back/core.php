<?php
    error_reporting(0);
    
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
    
    $core_data = file_get_contents(ROOT_PATH.'data/core.json');
    $core_data = json_decode($core_data, true);
    $advertisement_data = file_get_contents(ROOT_PATH.'data/advertisement.json');
    $advertisement_data = json_decode($advertisement_data, true);
    $tool_data = file_get_contents(ROOT_PATH.'data/tool.json');
    $tool_data = json_decode($tool_data, true);
    $tool_times_data = file_get_contents(ROOT_PATH.'data/tool_times.json');
    $tool_times_data = json_decode($tool_times_data, true);
    $link_data = file_get_contents(ROOT_PATH.'data/link.json');
    $link_data = json_decode($link_data, true);
    
    $keyword = $core_data['keyword'];
    foreach ($tool_data as $tool_data_key => $tool_data_value) {
        $keyword .= ','.$tool_data_value['name'].'工具';
    }
    
    if ($page_name == 'index.php') {
        $title = $core_data['title'].' - '.$core_data['subtitle'];
    } else if ($page_name == 'doc.php') {
        $doc_data = request_http('https://api.heroa.cn:3403/management/api/')[1];
        $doc_data = json_decode($doc_data, true);
        $doc_data = $doc_data['information'];
        $doc_root_times = $doc_data['root_times'];
        array_shift($doc_data);
        $api_category = $_GET['category'];
        $api_name = $_GET['name'];
        $is_doc = false;
        if (empty($api_category) or empty($api_name)) {
            $title = '文档 - '.$core_data['title'];
        } else {
            if (array_key_exists($api_category, $doc_data) and array_key_exists($api_name, $doc_data[$api_category])) {
                $is_doc = true;
                $title = '['.$doc_data[$api_category]['name'].']'.$doc_data[$api_category][$api_name]['name'].' - 文档 - '.$core_data['title'];
            } else {
                $title = '文档 - '.$core_data['title'];
            }
        }
    } else if ($page_name == 'about.php') {
        $title = '关于 - '.$core_data['title'];
    } else {
        $url_array = explode('/', $_SERVER['PHP_SELF']);
        if (count($url_array) != 4) {
            header('Location: /');
        }
        $tool_category = $url_array[2];
        $tool_name = str_replace('.php', '', $url_array[3]);
        
        $tool_times_file = fopen(ROOT_PATH.'data/tool_times.json', 'w');
        $tool_times_data[$tool_category][$tool_name] += 1;
        fwrite($tool_times_file, json_encode($tool_times_data, JSON_UNESCAPED_UNICODE));
        fclose($tool_times_file);
        
        $tool_title = $tool_data[$tool_category][$tool_name]['name'];
        $title = '['.$tool_data[$tool_category]['name'].']'.$tool_title.' - '.$core_data['title'];
        $keyword .= ','.$$tool_title.','.$tool_data[$tool_category][$tool_name]['keyword'];
    }
?>