<?php
/**
 * Class Twig
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc\View;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web\Ide as ide;

class Twig extends \Cmatrix\Web\Mvc\View {

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'CacheKey' : return $this->getMyCacheKey();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyCacheKey(){
        $Key = $this->Url .'.twig';
        
        if(!ide\Cache::get('forms')->isExists($Key)) throw new ex\Error($this,'twig template cache file [' .$this->Url. '] is not found.');
        return ide\Cache::get('dms')->getKey($Key);
    }

}
?>