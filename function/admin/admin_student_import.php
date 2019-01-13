<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function student_importHandle(){
    global $_E,$_G;

    try{
        if(!\userControl::has_permission('manage_student',$_G['uid'])){
            throw new \Exception('Access denied');
        }
        
        \Render::render('admin_student_import', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
