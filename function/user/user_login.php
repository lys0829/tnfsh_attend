<?php namespace TnfshAttend\User;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function loginHandle()
{
    global $_E,$_G;
    if( $_G['uid'] ) {
        \Render::ShowMessage('你不是登入了!?');
        exit(0);
    }

    $username = \TnfshAttend\safe_post('username');
    $AESenpass = \TnfshAttend\safe_post('password');
    $GB = \TnfshAttend\safe_post('GB');

    if( isset($username,$AESenpass,$GB) ) {
        if (!\userControl::CheckToken('LOGIN')) {
            \TnfshAttend\throwjson('error', 'token error, please refresh page');
        }

        //recover password
        $exkey = unserialize($_SESSION['dhkey']);
        $key = md5($exkey->decode($GB));
        $iv = $_SESSION['iv'];

        $decode = openssl_decrypt($AESenpass,'aes-256-cbc',$key,OPENSSL_ZERO_PADDING,$iv);
        $password = rtrim($decode, "\0");

        $user = login($username, $password);
        if (!$user[0]) {
            $_E['template']['alert'] = $user[1];
            \LOG::msg(\Level::Notice, "<$username> want to login but fail.(".$user[1].')');
            \TnfshAttend\throwjson('error', $user[1]);
        } else {
            $user = $user[1];
            \userControl::SetLoginToken($user['uid']);
            \TnfshAttend\throwjson('SUCC', 'index.php');
        }
    }else{
        \userControl::RegisterToken('LOGIN', 600);
        $exkey = new \TnfshAttend\DiffieHellman();
        $_SESSION['dhkey'] = serialize($exkey);
        $_SESSION['iv'] = \TnfshAttend\GenerateRandomString(16, SET_HEX);
        $_E['template']['dh_ga'] = $exkey->getGA();
        $_E['template']['dh_prime'] = $exkey->getPrime();
        $_E['template']['dh_g'] = $exkey->getG();
        $_E['template']['iv'] = $_SESSION['iv'];

        \Render::setbodyclass('loginbody');
        \Render::render('user_login_box', 'user');
        exit(0);
    }
}
