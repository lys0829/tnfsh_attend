<?php namespace TnfshAttend\Admin;
if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function admin_api_permission_manageHandle()
{
    global $_G,$_E;
    $data = \TnfshAttend\safe_post('gu',true);
    $pid = \TnfshAttend\safe_post('pid');
    $ad = (string)\TnfshAttend\safe_post('allow_or_deny');
    $edit = (string)\TnfshAttend\safe_post('group_or_user');

    try{
        if($ad!='allow' && $ad!='deny'){
            throw new \Exception('指定之修改政策無效');
        }
        if($edit!='group' && $edit!='user'){
            throw new \Exception('指定之修改目標無效');
        }

        $used = [];

        if(is_array($data)){
            foreach($data as $id){
                if($edit == 'group'){
                    $res = \userControl::change_permission_for_group($id,$pid,$ad,true);
                }
                else{
                    $res = \userControl::change_permission_for_user($id,$pid,$ad,true);
                }
                $used[$id] = true;
                if($res === false){
                    throw new \Exception('修改權限發生錯誤');
                }
            }
        }

        $alldata = [];
        if($edit == 'group'){
            $tname = \DB::tname('group_list');
            $res = \DB::fetchAll("SELECT `gid`,`gname_show` FROM `{$tname}`");
        }
        else{
            $tname = \DB::tname('account');
            $res = \DB::fetchAll("SELECT `uid`,`nickname` FROM `{$tname}`");
        }
        if($res === false){
            throw new \Exception('無法取得使用者或群組資料');
        }
        $alldata = $res;

        if($ad=='allow'){
            $ad='deny';
        }
        else{
            $ad='allow';
        }

        if($edit == 'user'){
            foreach($alldata as $user){
                if(!isset($used[$user['uid']])){
                    \userControl::delete_permission_from_user($pid,$user['uid'],true);
                }
            }
        }
        else{
            foreach($alldata as $group){
                if(!isset($used[$group['gid']])){
                    \userControl::delete_permission_from_group($pid,$group['gid'],true);
                }
            }
        }
        
        \TnfshAttend\throwjson('SUCC',$pid);
    }catch(\Exception $e){
        \TnfshAttend\throwjson('error',$e->getMessage());
    }
}