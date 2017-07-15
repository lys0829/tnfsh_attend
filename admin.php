<?php namespace TnfshAttend\Admin;


if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function AdminHandle()
{
    global $TnfshAttend,$_E,$_G;

    $param = $TnfshAttend->UriParam(1);
    switch( $param )
    {
        case 'group_manage':
            break;
        case 'group_list':
            break;
        case 'permission_list':
            break;
        case 'permission_manage':
            break;
        case 'api':
            break;

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/admin/admin_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();

    exit(0);
}
