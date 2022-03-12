var qq_state_label = document.getElementById('qq_state'),
    qq_number_label = document.getElementById('qq_number');

if (!get_cookie('qq_state')) {
    set_cookie('qq_state', 'QQ状态:未登录');
}
if (!get_cookie('qq_number')) {
    set_cookie('qq_number', 'QQ账号:未登录');
}

qq_state_label.innerHTML = get_cookie('qq_state');
qq_number_label.innerHTML = get_cookie('qq_number');

var qq_get_ing = false,
    qq_qr_sig = '',
    qq_qr_code = '',
    qq_redirect_android = '',
    qq_redirect_ios = '',
    check_qq_login_qr_code_handle = undefined,
    qq_check_login_qr_code_count = 0,
    qq_login_success = false;

function check_qq_login_qr_code() {
    qq_check_login_qr_code_count++;
    if (qq_check_login_qr_code_count > 120) {
        clearInterval(check_qq_login_qr_code_handle);
    }
    axios.get('https://api.heroa.cn:3403/qq/qr_code_login/?type=result&qr_sig=' + qq_qr_sig)
        .then(function(data) {
            data = data.data.information;
            if (typeof data == 'object') {
                var qq_number = data.base.qq_number,
                    super_cookie = data.super_cookie,
                    cookie = data.cookie;
                
                set_cookie('qq_state', 'QQ状态:已登录');
                set_cookie('qq_number', 'QQ账号:' + qq_number);
                set_cookie('superuin', super_cookie.superuin);
                set_cookie('superkey', super_cookie.superkey);
                set_cookie('supertoken', super_cookie.supertoken);
                set_cookie('uin', cookie.uin);
                set_cookie('skey', cookie.skey);
                set_cookie('p_uin', cookie.p_uin);
                set_cookie('p_skey', cookie.p_skey);
                set_cookie('pt4_token', cookie.pt4_token);
                set_cookie('g_tk', cookie.skey_g_tk);
                clearInterval(check_qq_login_qr_code_handle);
                axios.get('https://api.heroa.cn:3403/qq/convert_cookie/?platform=vip&superuin=' + super_cookie.superuin + '&superkey=' + super_cookie.superkey + '&supertoken=' + super_cookie.supertoken)
                    .then(function(data) {
                        data = data.data.information;
                        if (data != '未知错误') {
                            set_cookie('vip_uin', data.uin);
                            set_cookie('vip_skey', data.skey);
                            set_cookie('vip_p_uin', data.p_uin);
                            set_cookie('vip_p_skey', data.p_skey);
                            set_cookie('vip_pt4_token', data.pt4_token);
                            set_cookie('vip_g_tk', data.skey_g_tk);
                        }
                        $('#qq_qr_code_state').html('状态:已扫描');
                        qq_state_label.innerHTML = 'QQ状态:已登录';
                        qq_number_label.innerHTML = 'QQ账号:' + qq_number;
                        if (!qq_login_success) {
                            qq_login_success = true;
                            layer.alert('登录成功');
                        }
                });
            } else if (data == '已失效') {
                $('#qq_qr_code_state').html('状态:已失效(点击二维码刷新)');
                clearInterval(check_qq_login_qr_code_handle);
            } else {
                $('#qq_qr_code_state').html('状态:' + data);
            }
    });
}

function get_qq_login_qr_code() {
    delete_cookie('qq_state');
    delete_cookie('qq_number');
    delete_cookie('uin');
    delete_cookie('skey');
    delete_cookie('p_uin');
    delete_cookie('p_skey');
    delete_cookie('pt4_token');
    delete_cookie('g_tk');
    delete_cookie('permission_state');
    qq_state_label.innerHTML = 'QQ状态:未登录';
    qq_number_label.innerHTML = 'QQ账号:未登录';
    permission_state_label.innerHTML = '权限状态:未登录';
    
    if (!qq_get_ing) {
        var load = layer.load(0, {shade: false});
        
        qq_get_ing = true;
        
        axios.get('https://api.heroa.cn:3403/qq/qr_code_login/?type=get')
            .then(function(data) {
                data = data.data.information;
                qq_qr_sig = data.qr_sig;
                qr_code = data.qr_code;
                qq_redirect_android = data.redirect.android;
                qq_redirect_ios = data.redirect.ios;
                $('#qq_qr_code').html('<img onclick="get_qq_login_qr_code()" src="' + qr_code +
                    '" width="100%" title="点击刷新">');
                clearInterval(check_qq_login_qr_code_handle);
                check_qq_login_qr_code_handle = setInterval(check_qq_login_qr_code, 1000);
                qq_get_ing = false;
                qq_login_success = false;
                layer.close(load);
        });
    }
}

function qq_login() {
    get_qq_login_qr_code();
    
    Dialog.init(' \
                    <div id="qq_qr_code"></div> \
                    <div id="qq_qr_code_state"></div> \
                ', {
        title: '<p style="display: block; text-align: center;">QQ扫码[QQ]</p>',
        button: {
            跳转至QQ登录: function() {
                layer.confirm('登录后,请手动返回浏览器', function(index) {
                    layer.close(index)
                    if (is_android()) {
                        window.location.href = qq_redirect_android + 'weixin://';
                    } else {
                        window.location.href = qq_redirect_ios + 'weixin://';
                    }
                });
            },
            为什么扫码登录失败: function() {
                login_failure_reason();
            }
        }
    });
}

function qq_log_out() {
    delete_cookie('qq_state');
    delete_cookie('qq_number');
    delete_cookie('uin');
    delete_cookie('skey');
    delete_cookie('p_uin');
    delete_cookie('p_skey');
    delete_cookie('pt4_token');
    delete_cookie('g_tk');
    delete_cookie('permission_state');
    qq_state_label.innerHTML = 'QQ状态:未登录';
    qq_number_label.innerHTML = 'QQ账号:未登录';
    permission_state_label.innerHTML = '权限状态:未登录';
    layer.alert('退出成功');
}

function is_qq_login() {
    if (get_cookie('qq_state') == 'QQ状态:已登录') {
        return true;
    } else {
        return false;
    }
}

function get_qq_number() {
    return get_cookie('qq_number').replace('QQ账号:', '');
}