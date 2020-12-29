<?php
/**
 * Class View
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

abstract class View {
    protected $Form;

    // --- --- --- --- --- --- --- ---
    function __construct(\Cmatrix\Web\Ide\Form $form){
        $this->Form = $form;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data'      : return $this->getMyData();
            case 'CacheKey'  : return $this->getMyCacheKey();
            case 'CacheData' : return $this->getMyCacheData();
            default : throw new ex\Property($this,$name);
        }
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return string - template content
     */
    abstract protected function getMyData();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - template cache key
     * - имя файла в кеше
     */
    protected function getMyCacheKey(){
        $Key = $this->Form->CacheKey;
        $Cache = kernel\Ide\Cache::get('forms');
        
        if(CM_MODE === 'development' && !$Cache->isExists($Key)) throw new ex\Error('template cache file [' .$this->Form->Url. '] is not found.');
        return $Cache->getKey($Key);
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyCacheData(){
        $Key = $this->Form->CacheKey;
        $Cache = kernel\Ide\Cache::get('forms');
        return $Cache->getValue($Key);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(\Cmatrix\Web\Ide\Form $form){
        $ClassName = '\Cmatrix\Web\Mvc\View\\' . ucfirst($form->Type);
        return new $ClassName($form);
    }
}
?>