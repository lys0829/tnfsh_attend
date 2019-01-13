<?php namespace TnfshAttend\Admin;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function apiHandle()
{
    global $TnfshAttend,$_E;

    $param = $TnfshAttend->UriParam(2);
    switch( $param )
    {
        case 'group_manage':
            break;
        case 'permission_manage':
            break;
        case 'student_import':
            break;
            
        default:
            \TnfshAttend\throwjson('error', 'Access denied');
    }
    $funcpath = $_E['ROOT']."/function/admin/admin_api_$param.php";
    $func     = __NAMESPACE__ ."\\admin_api_{$param}Handle";

    require_once($funcpath);
    $func();
}