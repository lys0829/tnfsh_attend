<?php namespace TnfshAttend\Admin;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function permission_listHandle(){
    global $_E,$_G;

    try{
        
        $pname = \DB::tname('permission_list');
        $pres = \DB::fetchAll("SELECT * FROM `{$pname}`");
        if($pres === false){
            throw new \Exception('查無權限');
        }

        $_E['template']['permissions'] = $pres;
        
        \Render::render('admin_permission_list', 'admin');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
    
}
