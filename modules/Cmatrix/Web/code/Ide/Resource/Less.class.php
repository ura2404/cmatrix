<?php
/**
 * Class Cmatrix\Web\Ide\Resource\Less
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-29
 */

namespace Cmatrix\Web\Ide\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;
use \Cmatrix\Vendor as vendor;

class Less extends web\Ide\Resource {
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    protected function createCache(){
        vendor\Kernel::reg('ILess');

        $Parser = new \ILess\Parser();
        $Parser->parseFile($this->Path);
        $Css = $Parser->getCSS();
        
        $Cache = web\Ide\Cache::get('res');
        $Cache->updateValue($this->CacheKey,$Css);
    }
    
    // --- --- --- --- --- --- --- ---    
    protected function getMyHtml(){
        //return '<link rel="stylesheet/less" type="text/css" href="'. $this->Link .'"/>';
        return '<link rel="stylesheet" type="text/css" href="'. $this->Link .'"/>';
    }
}
?>