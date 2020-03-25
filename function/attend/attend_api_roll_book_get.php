<?php namespace TnfshAttend\Attend;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function attend_api_roll_book_getHandle()
{
    global $_G,$_E;
    $sign_id = \TnfshAttend\safe_post('sign_id');

    try{
        if(!\userControl::has_permission("view_roll_book",$_G['uid'])){
            \TnfshAttend\throwjson('error','Access denied');
        }
        $rbname = \DB::tname('roll_book');
        $res_rbook = \DB::fetch("SELECT * FROM `{$rbname}` WHERE `sign_id`=?",[$sign_id]);
        if($res_rbook === false){
            \TnfshAttend\throwjson('error','Not Found');
        }
        $rb = [];
        $rb['skip'] = json_decode($res_rbook["skip"]);
        $rb['late'] = json_decode($res_rbook["late"]);
        $rb['early'] = json_decode($res_rbook["early"]);
        \TnfshAttend\throwjson('SUCC',json_encode($rb));

    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}