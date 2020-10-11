<?php
/**
 * Class Html
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-27
 */

namespace Cmatrix\Mvc\View;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Html extends \Cmatrix\Mvc\View {

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        $Path = ide\Form::get($this->Url)->Path .'/form.html';
        if(!file_exists($Path)) throw new ex\Error($this,'html template file [' .$this->Url. '] is not found.');
        
        return file_get_contents($Path);
    }

}
?>