<?php
/**
 * Загрузка файлов
 */
 
require_once ('../../common.php');
require_once '../../utils.php';

header("Content-type: application/json");
//require_once '../../../.autoloader.php';

$folder = isset($_REQUEST['folder']) ? $_REQUEST['folder'] : null;
$files = isset($_FILES['files']) ? $_FILES['files'] : null;
if(!$folder || !$files) return;

$folder = $folder==='/' ? '/' : '/'.$folder.'/';

try{
    
    for($i = 0; $i < count($files['error']); $i++){
        
        $src_path = $files['tmp_name'][$i];
        $dst_path = RAW_ROOT . $folder. $files['name'][$i];
        //dump($dst_path,'DST');
        //dump($src_path,'SRC');
        
        if(!copy($src_path,$dst_path)) throw new \Exception('Файл не загружен');
    }
    
    echo json_encode([
        'status' => 1,
        'message' => 'Файл успешно загружен',
    ]);
    
}
catch(\Exception $e){
    echo json_encode([
        'status' => -1,
        'message' => $e->getMessage(),
    ]);
}


//dump($_REQUEST);
//dump($_FILES);

?>