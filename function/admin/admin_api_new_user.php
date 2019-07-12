<?php namespace TnfshAttend\Admin;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}
set_time_limit(0);
ignore_user_abort(true);
function admin_api_new_userHandle()
{
    global $_G,$_E;
    

    try{
        $username = \TnfshAttend\safe_post("username");
        $nickname = \TnfshAttend\safe_post("nickname");

        $acctname = \DB::tname('account');
        $res = \DB::fetch("SELECT * FROM `{$acctname}` WHERE `username` = ?",[$username]);
        if ($res) {
            throw new \Exception('帳號已存在');
        }

        $res = \DB::query("INSERT INTO `{$acctname}` (`username`,`nickname`) VALUES (?,?)",[$username,$nickname]);
        if ($res === false) {
            throw new \Exception('新增失敗');
        }
        \TnfshAttend\throwjson('SUCC',"新增成功");
    }catch(\Exception $e){
        \DB::query("rollback");
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}