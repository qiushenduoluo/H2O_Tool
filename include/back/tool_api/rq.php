<?php
//每天领人气接口
  
 header("content-type:text/json;charset=UTF-8");  
 $qq = $_GET["qq"] or $_POST["qq"];  
 $form = "禁止恶意调用";  
 $url = "http://apibb.rjk66.cn/rq.php?qq={$qq}";  
 $ch = curl_init();  
 curl_setopt($ch, CURLOPT_URL, $url);  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
 curl_setopt($ch, CURLOPT_HEADER, 0);  
 $output = curl_exec($ch);  
 $jx = json_decode($output, true);  
 curl_close($ch);  
 $gg = $jx["code"];  
if ($qq == '') { $data = array("code" => -1, "msg" => "请提交参数!", "form" => "{$form}");  
 exit(json_encode($data, JSON_UNESCAPED_UNICODE));  
}if ($gg == "4") { $msg = $jx["msg"];  
 $data = array("code" => 4, "msg" => "{$msg}", "form" => "{$form}");  
 exit(json_encode($data, JSON_UNESCAPED_UNICODE));  
}else{if ($gg == "0") { $msg = $jx["msg"];  
 $data = array("code" => 0, "msg" => "{$msg}", "form" => "{$form}");  
 exit(json_encode($data, JSON_UNESCAPED_UNICODE));  
}else{if ($gg == "6") { $msg = $jx["msg"];  
 $data = array("code" => 6, "msg" => "{$msg}", "form" => "{$form}");  
 exit(json_encode($data, JSON_UNESCAPED_UNICODE));  
}else{ $msg = $jx["msg"];  
 $data = array("code" => -1, "msg" => "{$msg}", "form" => "{$form}");  
 exit(json_encode($data, JSON_UNESCAPED_UNICODE));  
}}}