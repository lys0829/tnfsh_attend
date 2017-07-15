<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function group_manageHandle(){
    global $_E,$_G;

    $gid = (int)\TnfshAttend\safe_get('gid');

    try{
        $users = \userControl::get_group_users($gid);

        $alluser = [];
        $acctname = \DB::tname('account');
        $ugtname = \DB::tname('user_group');
        $ures = \DB::fetchAll("SELECT `uid`,`nickname` FROM `{$acctname}`");
        if($ures === false){
            throw new \Exception('無法取得使用者資料');
        }

        foreach($ures as $u){
            $res = \DB::fetch("SELECT `uid` FROM `{$ugtname}` WHERE `uid`=? AND `gid`!=?",[$u['uid'],$gid]);
            if($res === false){
                $alluser[] = $u;
            }
        }

        $gname = \DB::tname('group_list');
        $gres = \DB::fetch("SELECT * FROM `{$gname}` WHERE `gid`=?",[$gid]);
        if($gres === false){
            throw new \Exception('查無此群組');
        }
        $gru = [];
        foreach($users as $u){
            $gru[$u] = true;
        }

        $_E['template']['alluser'] = $alluser;
        $_E['template']['group_users'] = $gru;
        $_E['template']['group'] = $gres;
        \Render::render('admin_group_manage', 'admin');
    }
    catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}