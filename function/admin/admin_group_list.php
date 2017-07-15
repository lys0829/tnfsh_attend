<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function group_listHandle(){
    global $_E,$_G;

    try{
        if(!\userControl::has_permission('user_manage',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        
        $gname = \DB::tname('group_list');
        $gres = \DB::fetchAll("SELECT * FROM `{$gname}`");
        if($gres === false){
            throw new \Exception('查無群組');
        }

        $_E['template']['groups'] = $gres;
        
        \Render::render('admin_group_list', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
