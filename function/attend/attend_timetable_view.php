<?php namespace TnfshAttend\Attend;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function timetable_viewHandle(){
    global $_E,$_G;

    $class = (int)\TnfshAttend\safe_get('class');

    if(empty($class)){
        $_E['template']['message'] = "請選擇班級";
        \Render::renderSingleTemplate('common_message');
        exit(0);
    }

    $tbname = \DB::tname('timetable');
    $tbres = \DB::fetchAll("SELECT * FROM `{$tbname}` ORDER BY `start_time` ASC",[]);
    $tb_course = [];
    foreach($tbres as $co){
        $tb_course[$co['course_id']] = 
        [
            'course_name' => $co['course_name'],
            'course_id' => $co['course_id']
        ];
    }

    $date = strftime("%Y-%m-%d");
    $firstdate=strtotime($date)-date("w",strtotime($date))*86400;
    $lastdate=$firstdate+6*86400;
    
    $siname = \DB::tname('signed');
    $sires = \DB::fetchAll("SELECT * FROM `{$siname}` WHERE (`date` BETWEEN ? AND ?) AND `class`=?",[date("Y/m/d",$firstdate),date("Y/m/d",$lastdate),$class]);

    $signed = [];
    foreach($sires as $s){
        $cid = $s['course_id'];
        $d = date("w",strtotime($s['date']))-date("w",$firstdate);
        $nickn = \TnfshAttend\nickname($s['uid']);
        $nickn = $nickn[$s['uid']];
        $signed[$cid][$d]['nick'] = $nickn;
        $signed[$cid][$d]['sign_id'] = $s['sign_id'];
        if($_G['uid']==$s['uid']){
            $signed[$cid][$d]['own'] = true;
        }
        else{
            $signed[$cid][$d]['own'] = false;
        }
    }

    $_E['template']['tb_course'] = $tb_course;
    $_E['template']['class'] = $class;
    $_E['template']['firstdate'] = $firstdate;
    $_E['template']['date'] = $date;
    $_E['template']['signed'] = $signed;
    
    \Render::renderSingleTemplate('attend_timetable_view', 'attend');
}
