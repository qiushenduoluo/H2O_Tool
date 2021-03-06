<?php
    require_once './modular.php';
    
    $post_data = json_decode(file_get_contents('php://input'), true);
    $action = $post_data['action'];
    $value = $post_data['value'];
    if (empty($action) or empty($value)) {
        exit('参数错误');
    }
    if ($action == 'login') {
        if (md5(unicode_encode($value)) == read('core')['password']) {
            $_SESSION['login'] = true;
            exit('登录成功');
        }
        $_SESSION['login'] = false;
        exit('密码错误');
    }
    if (!$_SESSION['login']) {
        exit('未登录');
    }
    if ($action == 'clear_tool_times') {
        $data = array();
        write('tool_times', $data);
        exit('清空成功');
    } else if ($action == 'add_advertisement') {
        $data = read('advertisement');
        $data[$value['url']] = $value['image_url'];
        write('advertisement', $data);
        exit('添加成功');
    } else if ($action == 'delete_advertisement') {
        $data = read('advertisement');
        unset($data[$value]);
        write('advertisement', $data);
        exit('删除成功');
    } else if ($action == 'add_link') {
        $data = read('link');
        if (array_key_exists($value['name'], $data)) {
            exit('友链已存在');
        }
        $data[$value['name']] = $value['url'];
        write('link', $data);
        exit('添加成功');
    } else if ($action == 'delete_link') {
        $data = read('link');
        unset($data[$value]);
        write('link', $data);
        exit('删除成功');
    } else if ($action == 'revise_core') {
        $data = read('core');
        $data['title'] = $value['title'];
        $data['subtitle'] = $value['subtitle'];
        $data['keyword'] = $value['keyword'];
        $data['description'] = $value['description'];
        $data['ico_url'] = $value['ico_url'];
        $data['notice']['content'] = $value['notice_content'];
        $data['notice']['button_name'] = $value['notice_button_name'];
        $data['notice']['button_url'] = $value['notice_button_url'];
        $data['qq_group_url'] = $value['qq_group_url'];
        $data['verification']['qq_group_number'] = explode(',', $value['qq_group_number']);
        $data['notice']['open'] = $value['notice_open'];
        $data['verification']['open'] = $value['verification_open'];
        $data['custom_code']['css'] = base64_encode($value['custom_code_css']);
        $data['custom_code']['javascript'] = base64_encode($value['custom_code_javascript']);
        $data['custom_code']['pjax_success_javascript'] = base64_encode($value['custom_code_pjax_success_javascript']);
        $data['comment']['open'] = $value['comment_open'];
        $data['comment']['vercel_url'] = $value['comment_vercel_url'];
        $data['open_pjax'] = $value['open_pjax'];
        write('core', $data);
        exit('修改成功');
    }  else if ($action == 'revise_password') {
        $data = read('core');
        $data['password'] = md5(unicode_encode($value));
        write('core', $data);
        $_SESSION['login'] = false;
        exit('修改成功');
    }
?>