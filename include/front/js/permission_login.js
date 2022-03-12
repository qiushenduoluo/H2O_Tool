var permission_state_label = document.getElementById('permission_state');

if (!get_cookie('permission_state')) {
    set_cookie('permission_state', '权限状态:未登录');
}

permission_state_label.innerHTML = get_cookie('permission_state');

var permission_get_ing = false,
    permission_id = '',
    permission_qr_code = '',
    check_permission_login_qr_code_handle = undefined,
    permission_check_login_qr_code_count = 0,
    permission_login_success = false;

function check_permission_login_qr_code() {
    permission_check_login_qr_code_count++;
    if (permission_check_login_qr_code_count > 120) {
        clearInterval(check_permission_login_qr_code_handle);
    }
    axios.get(get_root_path() + 'include/back/permission_login.php?type=result&id=' + permission_id)
        .then(function(data) {
            data = data.data;
            if (data.state == '已登录') {
                clearInterval(check_permission_login_qr_code_handle);
                if (!permission_login_success) {
                    permission_login_success = true;
                    if (data.qq_number == get_qq_number()) {
                        set_cookie('permission_state', '权限状态:已登录');
                        permission_state_label.innerHTML = '权限状态:已登录';
                        $('#permission_qr_code_state').html('状态:已登录');
                        layer.alert('登录成功');
                    } else {
                        permission_state_label.innerHTML = '权限状态:失败';
                        $('#permission_qr_code_state').html('状态:失败');
                        layer.alert('登录失败,与QQ登录中的账号不匹配');
                    }
                }
            } else if (data == '已失效') {
                $('#permission_qr_code_state').html('状态:已失效(点击二维码刷新)');
                clearInterval(check_permission_login_qr_code_handle);
            } else {
                $('#permission_qr_code_state').html('状态:' + data);
            }
    });
}

function get_permission_login_qr_code() {
    delete_cookie('permission_state');
    
    if (!permission_get_ing) {
        var load = layer.load(0, {shade: false});
        
        permission_get_ing = true;
        
        axios.get('https://qr.rjk66.cn/api/gethzqr/')
            .then(function(data) {
                data = data.data.data;
                permission_id = data.id;
                qr_code = data.qrcode;
                $('#permission_qr_code').html('<img onclick="get_permission_login_qr_code()" src="' + qr_code +
                    '" width="100%" title="点击刷新">');
                clearInterval(check_permission_login_qr_code_handle);
                check_permission_login_qr_code_handle = setInterval(check_permission_login_qr_code, 1000);
                permission_get_ing = false;
                permission_login_success = false;
                layer.close(load);
        });
    }
}

function permission_login() {
    if (!is_qq_login()) {
        layer.alert('请先进行QQ登录');
    } else {
        get_permission_login_qr_code();
        
        Dialog.init(' \
                        <div id="permission_qr_code"></div> \
                        <div id="permission_qr_code_state"></div> \
                    ', {
            title: '<p style="display: block; text-align: center;">QQ扫码[权限]</p>',
            button: {
                为什么扫码登录失败: function() {
                    login_failure_reason();
                }
            }
        });
    }
}

function is_permission_login() {
    if (get_cookie('permission_state') == '权限状态:已登录') {
        return true;
    } else {
        return false;
    }
}

function permission_log_out() {
    delete_cookie('permission_state');
    permission_state_label.innerHTML = '权限状态:未登录';
    layer.alert('退出成功');
}