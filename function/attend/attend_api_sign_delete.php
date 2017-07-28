<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function attend_api_sign_deleteHandle()
{
    global $_G,$_E;
    $sign_id = \TnfshAttend\safe_post('sign_id');

    try{
        $stname = \DB::tname('signed');
        $rbname = \DB::tname('roll_book');

        $check_own = \DB::query("SELECT * FROM `{$stname}` WHERE `sign_id`=? AND `uid`=?",[$sign_id,$_G['uid']]);
        if($check_own === false){
            if(!\userControl::has_permission('modify_roll_book',$_G['uid'])){
                \TnfshAttend\throwjson('error','Access denied');
            }
        }

        $check = \DB::query("DELETE FROM `{$stname}` WHERE `sign_id` = ?",[$sign_id]);
        $check_rb = \DB::query("DELETE FROM `{$rbname}` WHERE `sign_id` = ?",[$sign_id]);
        if($check === false){
            throw new \Exception('delete signed error');
        }
        if($check_rb === false){
            throw new \Exception('delete roll_book error');
        }
        
        \TnfshAttend\throwjson('SUCC',"success");
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}