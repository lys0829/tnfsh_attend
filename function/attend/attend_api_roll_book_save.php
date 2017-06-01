<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function attend_api_roll_book_saveHandle()
{
    global $_G,$_E;
    $late = \TnfshAttend\safe_post('late');
    $skip = \TnfshAttend\safe_post('skip');
    $early = \TnfshAttend\safe_post('early');
    $sign_id = \TnfshAttend\safe_post('sign_id');

    try{
        $rbname = \DB::tname('roll_book');

        $res_rbook = \DB::query("UPDATE `{$rbname}` SET `late`=? , `skip`=? , `early`=? WHERE `sign_id`=? ",[$late,$skip,$early,$sign_id]);
        if( $res_rbook === false )
            throw new \Exception('update rbook error');
        
        \TnfshAttend\throwjson('SUCC',"saved");
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}