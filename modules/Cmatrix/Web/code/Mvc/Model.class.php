<?php
/**
 * Class Model
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web\Ide as ide;

class Model {
    protected $Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->getMyData();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        //dump('custom getMyData');
        
        $_content = function($name,$path){
            $Text = trim(file_get_contents($path));
            
            $Pos1 = strpos($Text,'MyModel');
            $Pos2 = strrpos($Text,'}');
            if($Pos1 === false) throw new ex\Error($this,'form [' .$this->Url. '] model is wrong.');
            
            $Text = substr($Text,$Pos1,$Pos2-$Pos1+1);
            $Text = str_replace('MyModel',$name,$Text);
            
            eval('class '. $Text);
        };
        
        $ClassName = str_replace('/','_',$this->Url) .'_model';
        $PathModel = ide\Form::get($this->Url)->Path .'/model.php';
        if(!file_exists($PathModel)) throw new ex\Error($this,'form [' .$this->Url. '] model is not defined.');
        
        if(!class_exists($ClassName)) $_content($ClassName,$PathModel);
        
        $Ob = new $ClassName($this->Url);
        $Data = $Ob->getData();
        
        $UrlParent = ide\Form::get($this->Url)->Parent;
        $DataParent = $UrlParent ? (new Model($UrlParent))->Data : [];
        
        $Data = arrayMergeReplace($DataParent,$Data);
        
        return $Data;
    }
}
?>