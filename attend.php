<?php namespace TnfshAttend\Attend;


if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function AttendHandle()
{
    global $TnfshAttend,$_E,$_G;

    $param = $TnfshAttend->UriParam(1);
    switch( $param )
    {
        case 'timetable':
            break;
        case 'timetable_view':
            break;
        case 'api':
            break;
        case 'roll_book':
            break;
        case 'search':
            break;
        case 'output':
            break;

        default:
            \Render::render('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/attend/attend_$param.php";
    $func     = __NAMESPACE__ ."\\{$param}Handle";

    require_once($funcpath);
    $func();

    exit(0);
}
