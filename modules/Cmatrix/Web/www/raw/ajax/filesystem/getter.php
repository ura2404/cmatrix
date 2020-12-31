<?php
header("Content-type: application/json");

require_once ('../../common.php');

$id = isset($_POST['id']) ? $_POST['id'] : null;
if(!$id) die();

$ace_path  = realpath(dirname(__FILE__).'/../../res/ace/1.2.3/src-noconflict');

// --- --- --- --- --- ---
$fun_getcontent = function() use($id){
    $file_path = RAW_ROOT.'/'.$id;
    return htmlspecialchars(file_get_contents($file_path));
};

// --- --- --- --- --- ---
$fun_gettype = function() use($id,$ace_path){
    // ---  получить массив допустимых типов
    $files = scandir($ace_path);
    $files = array_filter($files,function($value){ return (strpos($value,'mode-')!==false && strpos($value,'.js')!==false) ? true : false; });
    $types = array_map(function($value){
        return substr($value,5,strpos($value,'.js')-5);
    },$files);
    
    // --- определить расширение файла
    $count = substr_count($id,'.');                               // кол-во точек
    if(!$count) $ext = null;                                      // если в имени файла нет точек
    elseif(strpos($id,str_repeat('.',$count)) === 0) $ext = null; // если точки в начале
    else $ext = substr($id,strrpos($id,'.')+1);                   // нормальное расширение
    
    // --- тюннинг типа
    $arr_trans = [
		'txt' => 'plain_text',
		'js'  => 'javascript',
		'md'  => 'markdown',
		'py'  => 'python',
	];
	if(array_key_exists($ext,$arr_trans)) $ext = $arr_trans[$ext];  // заменим некоторые типы
	
	// ---  вернуть тип
	$ext = in_array($ext,$types) ? $ext : 'plain_text';             // проверим по массиву допустимых типов
	return $ext;
};

echo json_encode([
    'status'  => 1,
    'content' => $fun_getcontent(),
    'type'    => $fun_gettype()
]);

?>