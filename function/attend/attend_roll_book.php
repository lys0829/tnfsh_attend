<?php namespace TnfshAttend\Attend;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function roll_bookHandle(){
    global $_E,$_G;

    $sign_id = (int)\TnfshAttend\safe_get('sign_id');

    try{
        if(empty($sign_id)){
            throw new \Exception("尚未指定點名單");
        }

        $rbname = \DB::tname("roll_book");
        $stname = \DB::tname('signed');
        $clname = \DB::tname('class');
        $ttname = \DB::tname('timetable');
        $rbres = \DB::fetch("SELECT * FROM `{$rbname}` WHERE `sign_id`=?",[$sign_id]);
        if($rbres === false){
            throw new \Exception('查無點名單');
        }

        $stres = \DB::fetch("SELECT * FROM {$stname} WHERE `sign_id`=?",[$sign_id]);
        if($stres === false){
            throw new \Exception('此點名單尚未建立');
        }
        $class = $stres["class"];
        $course = $stres["course_id"];

        $clres = \DB::fetch("SELECT * FROM {$clname} WHERE `class`=?",[$class]);
        if($clres === false){
            throw new \Exception('查無班級');
        }

        $ttres = \DB::fetch("SELECT * FROM {$ttname} WHERE `course_id`=?",[$course]);
        if($ttres === false){
            throw new \Exception('system error');
        }

        $usid = -1;
        if($stres['course_id']>1){
            $usres = \DB::fetch("SELECT `sign_id` FROM {$stname} WHERE `class`=? AND `date`=? AND `course_id`=?",[$stres['class'],$stres['date'],$stres['course_id']-1]);
            if($usres!==false){
                $usid = $usres['sign_id'];
            }
        }

        $stnum = $clres["student"];
        $skip = json_decode($rbres["skip"]);
        $late = json_decode($rbres["late"]);
        $early = json_decode($rbres["early"]);

        $_E['template']['class'] = $class;
        $_E['template']['date'] = $stres["date"];
        $_E['template']['course_name'] = $ttres["course_name"];
        $_E['template']['sign_id'] = $sign_id;
        $_E['template']['stnum'] = $stnum;
        $_E['template']['skip'] = $skip;
        $_E['template']['skip_json'] = $rbres["skip"];
        $_E['template']['late'] = $late;
        $_E['template']['late_json'] = $rbres["late"];
        $_E['template']['early'] = $early;
        $_E['template']['early_json'] = $rbres["early"];
        $_E['template']['usid'] = $usid;
        
        \Render::render('attend_roll_book', 'attend');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
