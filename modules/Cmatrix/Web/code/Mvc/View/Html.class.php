<?php
/**
 * Class Html
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-27
 */

namespace Cmatrix\Web\Mvc\View;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web\Ide as ide;

class Html extends \Cmatrix\Web\Mvc\View {

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