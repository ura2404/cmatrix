<?php
/**
 * Class Cmatrix\Web\Ide\Resource\Custom
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-29
 */

namespace Cmatrix\Web\Ide\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Custom extends web\Ide\Resource {
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    protected function createCache(){
        $Cache = web\Ide\Cache::get('res');
        $Cache->updateFile($this->CacheKey,$this->Path);
    }
    
    // --- --- --- --- --- --- --- ---    
    protected function getMyHtml(){
    }
}
?>