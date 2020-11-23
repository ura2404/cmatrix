<?php
/**
 * Class Css
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-27
 */

namespace Cmatrix\Kernel\Ide\Resource;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Css {
    private $Url;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Link' : return $this->getMyLink();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyLink(){
        //return '<link rel="stylesheet" type="text/css" href="'. ide\Resource::get($this->Url)->Wpath .'"/>';
        return '<link rel="stylesheet" media="none" type="text/css" href="'. ide\Resource::get($this->Url)->Wpath .'" onload="if(media!=\'all\')media=\'all\'"/>';
    }
    
}
