<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function permission_manageHandle(){
    global $_E,$_G;

    $pid = (int)\TnfshAttend\safe_get('pid');
    $edit = (string)\TnfshAttend\safe_get('edit');
    $policy = (string)\TnfshAttend\safe_get('policy');

    try{
        if($policy != 'allow' && $policy != 'deny'){
            throw new \Exception('指定之修改政策無效');
        }
        if($edit == 'user'){
            $alluser = [];
            $acctname = \DB::tname('account');
            $ugtname = \DB::tname('user_permission');
            $ures = \DB::fetchAll("SELECT `uid`,`nickname` FROM `{$acctname}`");
            if($ures === false){
                throw new \Exception('無法取得使用者資料');
            }
            $alluser = $ures;

            $upres = \DB::fetchAll("SELECT * FROM `{$ugtname}` WHERE `pid`=? AND `group_or_user`='user' AND `allow_or_deny`=?",[$pid,$policy]);
            $peru = [];
            foreach($upres as $u){
                $peru[$u['gu']] = true;
            }

            $ptname = \DB::tname('permission_list');
            $pres = \DB::fetch("SELECT * FROM `{$ptname}` WHERE `pid`=?",[$pid]);
            if($pres === false){
                throw new \Exception('查無此權限');
            }
            

            $_E['template']['alluser'] = $alluser;
            $_E['template']['permission_users'] = $peru;
            $_E['template']['permission'] = $pres;
            $_E['template']['edit'] = $edit;
            $_E['template']['allow_or_deny'] = $policy;
            \Render::render('admin_permission_user_manage', 'admin');
        }
        else if($edit == 'group'){
            $allgroup = [];
            $grtname = \DB::tname('group_list');
            $ugtname = \DB::tname('user_permission');
            $gres = \DB::fetchAll("SELECT `gid`,`gname_show` FROM `{$grtname}`");
            if($gres === false){
                throw new \Exception('無法取得群組資料');
            }
            $allgroup = $gres;

            $upres = \DB::fetchAll("SELECT * FROM `{$ugtname}` WHERE `pid`=? AND `group_or_user`='group' AND `allow_or_deny`=?",[$pid,$policy]);
            $peru = [];
            foreach($upres as $u){
                $peru[$u['gu']] = true;
            }

            $ptname = \DB::tname('permission_list');
            $pres = \DB::fetch("SELECT * FROM `{$ptname}` WHERE `pid`=?",[$pid]);
            if($pres === false){
                throw new \Exception('查無此權限');
            }
            

            $_E['template']['allgroup'] = $allgroup;
            $_E['template']['permission_groups'] = $peru;
            $_E['template']['permission'] = $pres;
            $_E['template']['edit'] = $edit;
            $_E['template']['allow_or_deny'] = $policy;
            \Render::render('admin_permission_group_manage', 'admin');
        }
        else{
            throw new \Exception('指定之修改對象無效');
        }
        
    }
    catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}