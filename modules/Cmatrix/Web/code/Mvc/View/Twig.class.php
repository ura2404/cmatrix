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
use \Cmatrix\Web as web;

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
        $Key = $this->Form->CacheName;
        $Cache = web\Ide\Cache::get('forms');
        
        if(CM_MODE === 'development' && !$Cache->isExists($Key)) throw new ex\Error('twig template cache file [' .$this->Form->Url. '] is not found.');
        return $Cache->getKey($Key);
    }

}
?>