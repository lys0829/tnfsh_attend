<?php namespace TnfshAttend\Attend;

if (!defined('IN_TnfshAttendSYSTEM')) {
    exit('Access denied');
}

function searchHandle()
{
    global $_E,$_G;
    
    $begin = \TnfshAttend\safe_get('begin');
    $end = \TnfshAttend\safe_get('end');
    $class = (int)\TnfshAttend\safe_get('class');
    $num = (int)\TnfshAttend\safe_get('num');

    try{
        if(empty($begin)||empty($end)||empty($class)||empty($num)){
            $_E['template']['has_data'] = false;
        }
        else{
            $_E['template']['has_data'] = true;

            $signtname = \DB::tname("signed");
            if(!empty($class) && $class!=''){
                $signres = \DB::fetchAll("SELECT * FROM `{$signtname}` WHERE (`date` BETWEEN ? AND ?) AND `class`=? ORDER BY `date` ASC, `class` ASC, `course_id` ASC",[$begin,$end,$class]);
            }
            else{
                $signres = \DB::fetchAll("SELECT * FROM `{$signtname}` WHERE (`date` BETWEEN ? AND ?) ORDER BY `date` ASC, `class` ASC, `course_id` ASC",[$begin,$end]);
            }
            if($signres === false){
                $_E['template']['has_data'] = false;
                throw new \Exception('取得點名單資料出問題');
            }

            $ttname = \DB::tname("timetable");
            $tres = \DB::fetchAll("SELECT * FROM `{$ttname}` ORDER BY `start_time` ASC",[]);
            if($tres === false){
                $_E['template']['has_data'] = false;
                throw new \Exception('取得課表資料出問題');
            }
            
            //$st .= $s['date'].",".$s['class'].",";
            $roll_book_output = [];

            foreach($signres as $s){

                if(!isset($roll_book_output[$s['date']])){
                    $roll_book_output[$s['date']] = [];
                }

                if(!isset($roll_book_output[$s['date']][$s['class']])){
                    $roll_book_output[$s['date']][$s['class']] = [];
                }

                $rolltname = \DB::tname("roll_book");
                $rollres = \DB::fetch("SELECT * FROM `{$rolltname}` WHERE `sign_id` = ?",[$s['sign_id']]);
                if($rollres !== false){
                    $student = [];
                    $rollres['skip'] = json_decode($rollres['skip']);
                    $rollres['late'] = json_decode($rollres['late']);
                    $rollres['early'] = json_decode($rollres['early']);
                    if(is_array($rollres['skip'])){
                        foreach($rollres['skip'] as $sk){
                            $student[$sk] = "skip";
                        }
                    }

                    if(is_array($rollres['late'])){
                        foreach($rollres['late'] as $la){
                            $student[$la] = "late";
                        }
                    }

                    if(is_array($rollres['early'])){
                        foreach($rollres['early'] as $ea){
                            $student[$ea] = "early";
                        }
                    }

                    for($id = 1;$id<=45;$id++){
                        if($id!==$num)continue;
                        if(isset($student[$id])){
                            $roll_book_output[$s['date']][$s['class']][$id][$s['course_id']] = $student[$id];
                        }
                    }
                }
            }
            $_E['template']['roll_book'] = $roll_book_output;
            $_E['template']['timetable'] = $tres;
            $_E['template']['class'] = $class;
            $_E['template']['num'] = $num;
            $_E['template']['begin'] = $begin;
            $_E['template']['end'] = $end;
        }
        \Render::render('attend_search', 'attend');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}
