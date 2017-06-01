<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $TnfshAttend,$_E;

    $param = $TnfshAttend->UriParam(2);
    switch( $param )
    {
        case 'sign':
            break;
        case 'sign_delete':
            break;
        case 'roll_book_save':
            break;
            
        default:
            \TnfshAttend\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/attend/attend_api_$param.php";
    $func     = __NAMESPACE__ ."\\attend_api_{$param}Handle";

    require_once($funcpath);
    $func();
}