<?php

class Controller{

    private $View;
    private $Model;

    // --- --- --- --- ---- ---
    function __construct(View $view,Model $model){
        $this->View = $view;
        $this->Model = $model;
    }
    
    // --- --- --- --- ---- ---
    function __get($name){
        switch($name){
            case 'Html' : 
                $template = $this->View->Template;
                $model = $this->Model->Data;
                return $this->doIt($template,$model);
        }
    }
    
    // --- --- --- --- ---- ---
    private function doIt($template,$model){
        $arr = explode('{{',$template);
        foreach($arr as $key=>$value){
            if(  ($p=strpos($value,'}}'))  !==false){
                $index = trim(substr($value,0,$p));
                $data = array_key_exists($index,$model) ? $model[$index] : null;
                $value = $data.substr($value,$p+2);
            }
            $arr[$key] = $value;
        }
        return implode('',$arr);
    }
    
    // --- --- --- --- ---- ---
    static function get(View $view,Model $model){
        return new Controller($view,$model);
    }
    
}

?>