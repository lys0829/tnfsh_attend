<?php namespace TnfshAttend\Attend;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function timetableHandle()
{
    global $_E;
    
    $old = \TnfshAttend\safe_get('old');

    if(empty($old)){
        $old = "dashboard";
    }

    $class_list = [];
    $clname = \DB::tname('class');
    $res = \DB::fetchAll("SELECT `class` FROM `{$clname}`",[]);
    foreach($res as $cl){
        $class_list[] = $cl['class'];
    }

    $_E['template']['clist'] = $class_list;
    $_E['template']['old'] = $old;

    //\Render::renderSingleTemplate('attend_timetable', 'attend');
    \Render::render('attend_timetable', 'attend');
}
