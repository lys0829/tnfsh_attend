<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function user_listHandle(){
    global $_E,$_G;

    try{
        if(!\userControl::has_permission('user_manage',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        
        $acctname = \DB::tname('account');
        $signedname = \DB::tname('signed');
        $ugname = \DB::tname('user_group');
        $glname = \DB::tname('group_list');
        $res = \DB::fetchAll("SELECT `{$acctname}`.*,(SELECT count(*) FROM `{$signedname}` WHERE `{$signedname}`.uid = `{$acctname}`.uid) as `num`,(SELECT `gname_show` FROM `{$glname}` WHERE `gid` = (SELECT `gid` FROM `{$ugname}` WHERE `uid` = `{$acctname}`.`uid`)) as `group` FROM `{$acctname}` ORDER BY `nickname`");
        if($res === false){
            throw new \Exception('查詢資料失敗');
        }
        $_E['template']['users'] = $res;
        
        \Render::render('admin_user_list', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
