<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function attend_api_roll_book_saveHandle()
{
    global $_G,$_E;
    if(!\userControl::has_permission('modify_roll_book_limit',$_G['uid']) &&
    !\userControl::has_permission('modify_roll_book',$_G['uid'])){
        \TnfshAttend\throwjson('error','Access denied');
    }
    $late = \TnfshAttend\safe_post('late');
    $skip = \TnfshAttend\safe_post('skip');
    $early = \TnfshAttend\safe_post('early');
    $sign_id = \TnfshAttend\safe_post('sign_id');

    try{
        $stname = \DB::tname('signed');
        $res = \DB::fetch("SELECT * FROM `{$stname}` WHERE `sign_id`=? AND `uid`=?",[$sign_id,$_G['uid']]);
        if($res === false){
            \TnfshAttend\throwjson('error','Access denied');
        }
        if(time()-strtotime($res['timestamp']) > 172800){
            if(!\userControl::has_permission('modify_roll_book',$_G['uid']))
            {
                \TnfshAttend\throwjson('error','已過可修改之時限');
            }
        }

        $rbname = \DB::tname('roll_book');

        $res_rbook = \DB::query("UPDATE `{$rbname}` SET `late`=? , `skip`=? , `early`=? WHERE `sign_id`=? ",[$late,$skip,$early,$sign_id]);
        if( $res_rbook === false )
            throw new \Exception('update rbook error');
        
        \TnfshAttend\throwjson('SUCC',"saved");
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}