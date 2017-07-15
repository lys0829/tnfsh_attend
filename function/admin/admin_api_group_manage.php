<?php namespace TnfshAttend\Admin;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function admin_api_group_manageHandle()
{
    global $_G,$_E;
    $data = \TnfshAttend\safe_post('guser',true);
    $gid = \TnfshAttend\safe_post('gid');
    if(!\userControl::has_permission('user_manage',$_G['uid'])){
        \TnfshAttend\throwjson('error','Access denied');
    }

    try{
        $in_users = [];
        if(is_array($data)){
            foreach($data as $uid){
                $res = \userControl::add_user_to_group($uid,$gid);
                $in_users[$uid] = true;
                if($res === false){
                    throw new \Exception('add user to group error');
                }
            }
        }

        $alluser = [];
        $acctname = \DB::tname('account');
        $ures = \DB::fetchAll("SELECT `uid`,`nickname` FROM `{$acctname}`");
        if($ures === false){
            throw new \Exception('無法取得使用者資料');
        }
        $alluser = $ures;

        foreach($alluser as $user){
            if(!isset($in_users[$user['uid']])){
                \userControl::delete_user_from_group($user['uid'],$gid);
            }
        }
        
        \TnfshAttend\throwjson('SUCC',$gid);
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}