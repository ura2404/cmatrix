<?php
header("Content-type: application/json");
require_once ('../../common.php');
require_once '../../utils.php';

$mode = empty($_POST['mode']) ? null : $_POST['mode'];
$params = empty($_POST['params']) ? null : $_POST['params'];

//$root_path = realpath(dirname(__FILE__).'/../../../../');
$root_path = RAW_ROOT;

/**
 * рекурсивное получение массива имён файлов без $root_path
 */
$fun_rec = function($path,&$arr=[]) use($root_path,&$fun_rec){
    $arr[] = $path;
    
    if(is_dir($root_path.'/'.$path)){
        $dir = scandir($root_path.'/'.$path);
        foreach($dir as $p){
            if($p === '.' || $p === '..') continue;
            $np = $path .'/'. $p;
            if(is_dir($root_path.'/'.$np)) $fun_rec($np,$arr);
            else $arr[] = $np;
        }
    }
    return $arr;
};

/**
 * Копирование
 */
$fun_copy = function($oldid,$newid) use($root_path,$fun_rec){
    //$newid = $newid==='/' ? '' : $newid;
    
    $arr = $fun_rec($oldid);
    //dump($arr);
    
    $rem = str_rbefore($oldid,'/',true);
    //dump($oldid);
    //dump($rem,'REM');
    
    foreach($arr as $path){
        $op = $root_path .'/'. $path;
        $np = $root_path .'/'. $newid. '/'. str_after($path,$rem);
        $np = str_replace('///','/',$np);
        
        //dump($op);
        //dump($np);
        //dump(is_dir($op));
        //dump('---------------------------------');
        
        if(is_dir($op)){
            if(mkdir($np,0770) === false) throw new \Exception('Папка [' .$path. '] не создана');
        }
        else{
            if(copy($op,$np) === false) throw new \Exception('Не удалось скопировать файл [' .$path. ']');
        }
    }
     
 };

/**
 * Копирование
 */
 $fun_move = function($oldid,$newid) use($root_path,$fun_rec){
    $op = $root_path .'/'. $oldid;
    $np = $root_path .'/'. $newid  .'/'. str_rafter($oldid,'/');
    $np = str_replace('///','/',$np);
    
    //dump($op);
    //dump($np);
    if(rename($op,$np) === false) throw new \Exception('Не удалось скопировать переместить [' .$oldid. ']');
 };

try{
    $message = null;
    $data = null;
    switch($mode){
        
        case 'rename' :
            $newid = dirname($params['oldid']) .'/'. $params['newname'];
            $oldpath = $root_path .'/'. $params['oldid'];
            $newpath = dirname($oldpath) .'/'. $params['newname'];
            
            if(rename($oldpath,$newpath) === false) throw new \Exception('Не удалось переименовать');
            $message = 'Переименование успешно выполнено';
            $data = $newid;
            break;
            
        case 'file' :
            $newpath = $root_path .'/'. $params['oldid'] .'/'. $params['newname'];
            $type = is_dir($newpath) ? 'folder' : 'file';
            
            if(file_exists($newpath)){
                if($type === 'folder') throw new \Exception('Уже существует папка с таким именем');
                else throw new \Exception('Файл [' .$params['newname']. '] уже существует');
            }
            
            file_put_contents($newpath,null);
            $um = umask(0);
            chmod($newpath,0660);
            umask($um);
            if(!file_exists($newpath)) throw new \Exception('Файл [' .$params['newname']. '] не создан');
            $message = 'Файл успешно создан';
            break;
            
        case 'folder' :
            $newpath = $root_path .'/'. $params['oldid'] .'/'. $params['newname'];
            $type = is_dir($newpath) ? 'folder' : 'file';
            
            if(file_exists($newpath)){
                if($type === 'folder') throw new \Exception('Папка [' .$params['newname']. '] уже существует');
                else throw new \Exception('Уже существует файл с таким именем');
            }
            $um = umask(0);
            if(mkdir($newpath,0770) === false) throw new \Exception('Папка [' .$params['newname']. '] не создана');
            umask($um);
            $message = 'Папка успешно создана';
            break;
            
        case 'copy' :
            $fun_copy($params['oldid'],$params['newid']);
            $message = 'Копирование успешно завершено';
            break;

        case 'move' :
            $fun_move($params['oldid'],$params['newid']);
            $message = 'Перемещение успешно завершено';
            break;
            
        case 'delete' :
            $oldpath = $root_path .'/'. $params['oldid'];
            $type = is_dir($oldpath) ? 'folder' : 'file';
            
            if(!file_exists($oldpath)){
                if($type === 'folder') throw new \Exception('Папка [' .$params['oldname']. '] не существует');
                else throw new \Exception('Файл [' .$params['oldname']. '] не существует');
            }
            
            if($type === 'folder'){
                $arr = $fun_rec($params['oldid']);
                
                foreach(array_reverse($arr) as $path){
                    $np = $root_path .'/'. $path;
                    if(is_dir($np)){
                        if(rmdir($np) === false) throw new \Exception('Не удалось удалить папку [' .$path. ']');
                    }
                    else{
                        if(unlink($np) === false) throw new \Exception('Не удалось удалить файл [' .$path. ']');
                    }
                    
                }
                $message = 'Папка успешно удалена';
            }
            else{
                if(unlink($oldpath) === false) throw new \Exception('Не удалось удалить файл');
                $message = 'Файл успешно удалён';
            }
            break;
            
        /**
         * Получить список
         */
        case 'getFiles' :
            $data = $fun_rec($params['oldid']);
            break;
            
        default : throw new \Exception('Не определённый режим [' .$mode. ']');
    }
    
    echo json_encode([
        'status' => 1,
        'message' => $message,
        'data' => $data,
    ]);
}
catch(\Exception $e){
    echo json_encode([
        'status' => -1,
        'message' => $e->getMessage(),
    ]);
}

?>