<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function new_userHandle(){
    global $_E,$_G;

    try{
        if(!\userControl::has_permission('user_manage',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        
        \Render::render('admin_user_new', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
