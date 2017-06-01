<?php namespace TnfshAttend\User;


if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

require_once 'function/user/user.lib.php';
function UserHandle()
{
    global $TnfshAttend,$_E,$_G;
    $param = $TnfshAttend->UriParam(1)??($_G['uid']?'view':'login');

    switch( $param )
    {
        //api
        case 'edit':
            break;

        case 'login':
            break;
        case 'logout':
            break;
        case 'view':

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/user/user_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();
}
