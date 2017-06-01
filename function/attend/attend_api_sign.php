<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function attend_api_signHandle()
{
    global $_G,$_E;
    $date = \TnfshAttend\safe_post('sign_date');
    $course_id = \TnfshAttend\safe_post('sign_course_id');
    $classnum = \TnfshAttend\safe_post('sign_class');

    try{
        $stname = \DB::tname('signed');
        $rbname = \DB::tname('roll_book');

        $check = \DB::fetch("SELECT * FROM {$stname} WHERE `date`=? AND `course_id`=? AND `class`=?",[$date,$course_id,$classnum]);
        if($check !== false){
            throw new \Exception('已點過名');
        }
        
        $res = \DB::query("INSERT INTO `{$stname}` (`sign_id`, `date`, `course_id`, `uid`, `class`, `timestamp`) VALUES (NULL,?,?,?,?, CURRENT_TIMESTAMP)",[$date,$course_id,$_G['uid'],$classnum]);
        if( $res === false )
            throw new \Exception('insert signed error');
        
        $sign_id = \DB::lastInsertId('sign_id');
        $res_rbook = \DB::query("INSERT INTO `{$rbname}` (`sign_id`,`late`,`skip`,`early`) VALUES (?,?,?,?)",[$sign_id,"[]","[]","[]"]);
        if( $res_rbook === false )
            throw new \Exception('insert rbook error');
        
        \TnfshAttend\throwjson('SUCC',$sign_id);
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}