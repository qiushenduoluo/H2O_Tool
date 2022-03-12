<?php
    require_once ROOT_PATH.'include/back/modular.php';
    
    $core_data = read('core');
    $verification = $core_data['verification'];
    $advertisement_data = read('advertisement');
    $tool_data = read('tool');
    $tool_times_data = read('tool_times');
    $link_data = read('link');
    
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
    } else if ($page_name == 'management.php') {
        $title = '管理 - '.$core_data['title'];
    }  else {
        $url_array = explode('/', $_SERVER['PHP_SELF']);
        if (count($url_array) != 4) {
            header('Location: /');
        }
        $tool_category = $url_array[2];
        $tool_name = str_replace('.php', '', $url_array[3]);
        
        $tool_times_data[$tool_category][$tool_name] += 1;
        write('tool_times', $tool_times_data);
        
        $tool_title = $tool_data[$tool_category][$tool_name]['name'];
        $title = '['.$tool_data[$tool_category]['name'].']'.$tool_title.' - '.$core_data['title'];
        $keyword .= ','.$$tool_title.','.$tool_data[$tool_category][$tool_name]['keyword'];
    }
?>