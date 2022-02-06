var verification_state_label = document.getElementById('verification_state');

if (!get_cookie('verification_state')) {
    set_cookie('verification_state', '验证状态:未入QQ群');
}

verification_state_label.innerHTML = get_cookie('verification_state');

var verification_get_ing = false;
    verification_qr_sig = '',
    verification_qr_code = '',
    check_verification_login_qr_code_handle = undefined;

function check_verification_login_qr_code() {
    var check_login_qr_code_count = 0;
    
    check_login_qr_code_count++;
    if (check_login_qr_code_count > 120) {
        clearInterval(check_verification_login_qr_code_handle);
    }
    axios.get('https://api.heroa.cn:3403/qq/qr_code_login/?platform=group&type=result&qr_sig=' + verification_qr_sig)
        .then(function(data) {
            data = data.data.information;
            if (typeof data == 'object') {
                var cookie = data.cookie;
                
                $('#verification_qr_code_state').html('状态:已扫描');
                
                axios.get(get_root_path() + 'include/back/verification_login.php?skey=' + cookie.skey + '&p_uin=' + cookie.p_uin + '&p_skey=' + cookie.p_skey)
                    .then(function(data) {
                        data = data.data;
                        if (data == '验证成功') {
                            set_cookie('verification_state', '验证状态:已加入QQ群');
                            verification_state_label.innerHTML = '验证状态:已加入QQ群';
                            layer.alert('验证成功');
                        } else {
                            layer.open({
                                content: '验证失败,是否前往加入QQ群'
                                ,btn: ['是', '取消']
                                ,yes: function(index, layero){
                                    window.location.href = data;
                                }
                            });
                        }
                        clearInterval(check_verification_login_qr_code_handle);
                        return ;
                });
            } else if (data == '已失效') {
                $('#verification_qr_code_state').html('状态:已失效(点击二维码刷新)');
                clearInterval(check_verification_login_qr_code_handle);
                return ;
            } else {
                $('#verification_qr_code_state').html('状态:' + data);
            }
    });
}

function get_verification_login_qr_code() {
    delete_cookie('verification_state');
    verification_state_label.innerHTML = '验证状态:未入QQ群';
    
    if (!verification_get_ing) {
        var load = layer.load(0, {shade: false});
        
        verification_get_ing = true;
        
        axios.get('https://api.heroa.cn:3403/qq/qr_code_login/?platform=group&type=get')
            .then(function(data) {
                data = data.data.information;
                verification_qr_sig = data.qr_sig;
                qr_code = data.qr_code;
                $('#verification_qr_code').html('<img onclick="get_verification_login_qr_code()" src="' + qr_code +
                    '" width="100%" title="点击刷新">');
                clearInterval(check_verification_login_qr_code_handle);
                check_verification_login_qr_code_handle = setInterval(check_verification_login_qr_code, 1000);
                verification_get_ing = false;
                layer.close(load);
        });
    }
}

function verification_login() {
    get_verification_login_qr_code();
    
    Dialog.init(' \
                    <div id="verification_qr_code"></div> \
                    <div id="verification_qr_code_state"></div> \
                ', {
        title: '<p style="display: block; text-align: center;">QQ扫码[验证]</p>',
        button: {
            为什么扫码登录失败: function() {
                login_failure_reason();
            }
        }
    });
}

function verification_log_out() {
    delete_cookie('verification_state');
    verification_state_label.innerHTML = '验证状态:未入QQ群';
    layer.alert('退出成功');
}

function is_verification_success() {
    if (get_cookie('verification_state') == '验证状态:已加入QQ群') {
        return true;
    } else {
        return false;
    }
}