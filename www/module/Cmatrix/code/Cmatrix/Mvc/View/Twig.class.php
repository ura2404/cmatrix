<?php
/**
 * Class Twig
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Mvc\View;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Twig extends \Cmatrix\Mvc\View {

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'PathCache' : return $this->getMyPathCache();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyPathCache(){
        $Path = str_replace('/','_',$this->Url) .'.twig';
        if(!file_exists(kernel\Ide\Cache::get('forms')->Path .'/'. $Path)) throw new ex\Error($this,'twig template cache file [' .$this->Url. '] is not found.');
        return $Path;
    }

}
?>