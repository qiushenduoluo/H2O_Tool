<?php require_once './include/front/header.php'; ?>
        
        <?php
            if (!$is_doc) {
                echo '
                    <br/>
                    <span class="layui-badge">今日调用</span> <span class="layui-badge layui-bg-green">'.$doc_root_times['today'].'</span>
                    <span class="layui-badge layui-bg-orange">历史调用</span> <span class="layui-badge layui-bg-green">'.$doc_root_times['total'].'</span>
                    <div class="layui-tab layui-tab-brief">
                        <ul class="layui-tab-title">
                ';
                $count = 0;
                foreach ($doc_data as $doc_data_key => $doc_data_value) {
                    if ($count == 0) {
                        echo '<li class="layui-this">'.$doc_data_value['name'].'</li>';
                        $count += 1;
                    } else {
                        echo '<li>'.$doc_data_value['name'].'</li>';
                    }
                }
                echo '
                        </ul>
                    <div class="layui-tab-content">
                ';
                $count = 0;
                foreach ($doc_data as $doc_data_key => $doc_data_value) {
                    if ($count == 0) {
                        echo '<div class="layui-tab-item layui-show">';
                        $count += 1;
                    } else {
                        echo '<div class="layui-tab-item">';
                    }
                    echo '
                        <div class="layui-bg-gray" style="padding: 5px;">
                            <div class="layui-row layui-col-space10">
                    ';
                    foreach ($doc_data_value as $doc_data_value_key => $doc_data_value_value) {
                        if ($doc_data_value_key != 'name') {
                            echo '
                                <div class="layui-col-md3">
                                    <div class="layui-card">
                                        <div class="layui-card-header">
                                            <a href="./doc.php?category='.$doc_data_key.'&name='.$doc_data_value_key.'">'.$doc_data_value_value['name'].'</a>
                                        </div>
                                        <div class="layui-card-body">'
                                            .$doc_data_value_value['description'].'<br/>
                                            使用次数:'.$doc_data_value_value['times'].
                                        '</div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    echo '
                            </div>
                        </div>
                    </div>
                    ';
                }
                echo '
                        </div>
                    </div>
                ';
            } else {
                $single_doc = $doc_data[$api_category][$api_name];
                $api_url = 'https://api.heroa.cn:3403/'.$api_category.'/'.$api_name.'/';
                if (array_key_exists('request', $single_doc)) {
                    $single_doc_request = $single_doc['request'];
                }
                else{
                    $single_doc_request = false;
                }
                
                echo '
                    <div class="layui-tab layui-tab-brief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">详情</li>
                            <li>示例</li>
                            <li>状态码</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>基本</legend>
                                </fieldset>
                                
                                <span class="layui-badge layui-bg-black">API名称</span> <span class="layui-badge layui-bg-green">'.$single_doc['name'].'</span><br/><br/>
                                <span class="layui-badge">请求网址</span> <span class="layui-badge layui-bg-green">'.$api_url.'</span><br/><br/>
                                <span class="layui-badge layui-bg-orange">请求方式</span> <span class="layui-badge layui-bg-green">GET / POST</span><br/><br/>
                                <span class="layui-badge layui-bg-blue">响应格式</span> <span class="layui-badge layui-bg-green">JSON</span><br/>
                ';
                if ($single_doc_request) {
                    echo '
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>请求参数</legend>
                        </fieldset>
                        <table class="layui-table" lay-skin="row" lay-even="">
                            <colgroup>
                                <col width="20%"/>
                                <col width="20%"/>
                                <col width="60%"/>
                                <col/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>必填</th>
                                    <th>说明</th>
                                </tr>
                            </thead>
                            <tbody>
                    ';
                    foreach ($single_doc_request as $single_doc_request_key => $single_doc_request_value) {
                        if ($single_doc_request_value['require']) {
                            $require = '是';
                        }
                        else {
                            $require = '否';
                        }
                        echo '
                            <tr>
                                <td>'.$single_doc_request_key.'</td>
                                <td>'.$require.'</td>
                                <td>'.$single_doc_request_value['explain'].'</td>
                            </tr>
                        ';
                    }
                    echo '    	
                            </tbody>
                        </table>
                    ';
                }
                echo '
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>响应参数</legend>
                        </fieldset>
                    
                    </div>
                    
                    <div class="layui-tab-item">
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>JavaScript</legend>
                            </fieldset>
<pre class="layui-code">let post_data = {
                ';
                foreach ($single_doc_request as $single_doc_request_key => $single_doc_request_value) {
                    if ($single_doc_request_value == end($single_doc_request)) {
    echo '"'.$single_doc_request_key.'" = ""';
                    } else {
    echo '"'.$single_doc_request_key.'" = "",';
                    }
                }
                echo '
};
axios.post("'.$api_url.'", post_data)
    .then(function(data){
        data = data.data
});</pre>
                    
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>PHP</legend>
                            </fieldset>
<pre class="layui-code">function request_http($url, $type=0, $post_data="", $ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.58", $cookie="", $header=array(), $redirect=true){
    // 初始化curl
    $curl = curl_init();
    // 设置网址
    curl_setopt($curl,CURLOPT_URL, $url);
    // 设置Ua
    if (!empty($ua)) {
    	$header[] = "User-Agent:".$ua;
    }
    // 设置Cookie
    if (!empty($cookie)) {
    	$header[] = "Cookie:".$cookie;
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
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == "200") {
    	$return_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $return_header = substr($return, 0, $return_header_size);
        $return_data = substr($return, $return_header_size);
    }
    // 关闭cURL
    curl_close($curl);
    // 返回数据
    return [$return_header, $return_data];
}
$post_data = "
                ';
                foreach ($single_doc_request as $single_doc_request_key => $single_doc_request_value) {
                                if ($single_doc_request_value == end($single_doc_request)) {
                echo $single_doc_request_key.' =";';
                                } else {
                echo $single_doc_request_key.' = &';
                                }
                            }
                echo '
$data = request_http("'.$api_url.'", 1, $post_data)[1];
$data = json_decode($data, true);</pre>
                    </div>
                            
                        <div class="layui-tab-item">
                            <table class="layui-table" lay-skin="row" lay-even="">
                                <colgroup>
                                    <col width="30%"/>
                                    <col width="70%"/>
                                    <col/>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>状态码</th>
                                        <th>说明</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <tr>
                                            <td>100</td>
                                            <td>错误</td>
                                        </tr>
                                        <tr>
                                            <td>200</td>
                                            <td>成功</td>
                                        </tr>
                                        <tr>
                                            <td>500</td>
                                            <td>未知错误</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                ';
            }
        ?>
        
<?php require_once './include/front/footer.php'; ?>