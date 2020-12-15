<?php
/**
 * Class Model
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Model {
    protected $Form;

    // --- --- --- --- --- --- --- ---
    function __construct(\Cmatrix\Web\Ide\Form $form){
        $this->Form = $form;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->getMyData();
            default : throw new ex\Property($this,$name);
        }
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        //dump('custom getMyData');
        
        $_content = function($name,$path){
            $Text = trim(file_get_contents($path));
            
            $Pos1 = strpos($Text,'MyModel');
            $Pos2 = strrpos($Text,'}');
            if($Pos1 === false) throw new ex\Error('form [' .$this->Url. '] model is wrong.');
            
            $Text = substr($Text,$Pos1,$Pos2-$Pos1+1);
            $Text = str_replace('MyModel',$name,$Text);
            
            eval('class '. $Text);
        };
        
        $ClassName = str_replace('/','_',$this->Form->Url) .'_model';
        
        $PathModel = $this->Form->Path .'/model.php';
        if(!file_exists($PathModel)) throw new ex\Error('form [' .$this->Url. '] model is not defined.');
        
        if(!class_exists($ClassName)) $_content($ClassName,$PathModel);
        
        $Ob = new $ClassName($this->Form);
        if(!is_array($Data = $Ob->getData())) $Data = [];
        
        $DataParent = $this->Form->Parent ? self::get($this->Form->Parent)->Data : [];
        $Data = arrayMergeReplace($DataParent,$Data);
        return $Data;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($form){
        $ClassName = '\Cmatrix\Web\Mvc\Model\\' . ucfirst($form->Type);
        return new $ClassName($form);
    }
}
?>