<?php

class Model{
    
    // --- --- --- --- ---- ---
    function __get($name){
        switch($name){
            case 'Data' :
                return [
                    'now' => date('Y'),
                    'home' => $this->home(),
                    'whome' => $this->whome(),
                    'version' => '1.1',
                ];
        }
        
    }    

    // --- --- --- --- ---- ---
    function home(){
		$path = __FILE__;
		$path = str_replace(DIRECTORY_SEPARATOR,'/',dirname($path));
		$arr1 = explode('/',ltrim($path,'/'));
		array_pop($arr1);
		array_pop($arr1);
		
		return (DIRECTORY_SEPARATOR === '/' ? '/' : '') .implode('/',$arr1);
    }

    // --- --- --- --- ---- ---
    function whome(){
		$arr1 = explode('/',$this->home());
		$arr2 = explode('/',ltrim($_SERVER["REQUEST_URI"],'/'));
		$arr = $this->intersectArray($arr1,$arr2);
		return is_array($arr) ? '/'.implode('/',$arr) : null;
    }

    // --- --- --- --- ---- ---
    function intersectArray($arr1,$arr2){
        $arr_ret = NULL;
        $arr_tmp = $arr1;
        krsort($arr_tmp);
        foreach($arr_tmp as $key=>$value){
            if($arr2[0]==$value){
                $a1 = array_slice($arr1,$key);
                $a2 = array_slice($arr2,0,count($a1));
                if($a1===$a2) $arr_ret = $a1;
            }
        }
        return $arr_ret;
    }
    
    // --- --- --- --- ---- ---
    static function get(){
        return new Model();
    }

}

?>