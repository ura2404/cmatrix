<?php
/**
 * Записать файл из редактора
 */
use \Cmatrix as cm;

require_once '../../utils.php';

header("Content-type: application/json");

$file = isset($_POST['file']) ? $_POST['file'] : null;
$content = isset($_POST['content']) ? $_POST['content'] : null;

if(!$file || $content===null) die('Bye');

$root_path = realpath(dirname(__FILE__).'/../../../../');

try{
    $path = $root_path .'/'. $file;
    
	if(!file_exists($path)) throw new \Exception('Файл [' .$file. '] не существует');
	if(file_exists($path) && !is_writable($path)) throw new \Exception('Невозможно записать файл [' .$file. ']. Вероятнее всего недостаточно прав.');
    
	$length = file_put_contents($path,$content);
	if($length !== strlen($content)) throw new \Exception('Файл [' .$file. '] записан не верно');
    
	// --- временный костыль
	// --- файл *.twig скопировать в кэш
    $fun_project = function($arr){
        foreach($arr as $key=>$a) if($a === '.pro') return $arr[$key+1];
    };
    $fun_name = function($arr){
        foreach($arr as $key=>$a){
            if($a === '.pro'){
                unset($arr[$key]);
                unset($arr[$key+1]);
                unset($arr[$key+2]);
                return implode('_',$arr);
                break;
            }
            else unset($arr[$key]);
        };
        $arr = array_reverse($arr);
        $a1 = array_shift($arr);
        $a2 = array_shift($arr);
        return $a2 .'_'. $a1;
    };
    
	if(strEnd(basename($path),'.twig')){
        $arr = explode('/',$path);
        $project = $fun_project($arr);
        $name = $fun_name($arr);
        $new_path = $root_path.'/.cache/forms/'.$project.'_'.$name;
        $new_path = strRBefore($new_path,'_form.twig') . '.twig';
        
        //throw new \Exception($new_path);
        //dump($path);
        //dump($new_path);
        
        $res = copy($path,$new_path);
        if(!$res) throw new \Exception('Не создан кэш шаблона.');
        //dump($res);
	}
	
	// --- файл *.less компилировать и положить в кэш
    elseif(strEnd(basename($path),'.less')){
        if(strpos($path,'.pro') !== false && strpos($path,'.pro/vendor') === false){
            $OldPath = $path;
            $Arr = explode('.pro/',$path);
            $Arr[1] = str_replace(['/','less'],['_','css'],$Arr[1]);
            $NewPath = implode('.cache/less/',$Arr);
            
            require_once '../../php/ILess/Autoloader.php';
            \ILess\Autoloader::register();
            $Parser = new \ILess\Parser();
            $Parser->parseFile($OldPath);
            $Css = $Parser->getCSS();
            file_put_contents($NewPath,$Css);
        }
	}
	
    echo json_encode([
        'status' => 1,
        'message' => 'Файл успешно сохранён'
    ]);
	
}
catch(\Exception $e){
	echo json_encode([
		'status' => -1,
		'message' => $e->getMessage()
	]);
}
?>