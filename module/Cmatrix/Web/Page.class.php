<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web;
//use \Cmatrix as cm;
//use \Cmatrix\Kernel as kernel;
//use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;
//use \Cmatrix\Mvc as mvc;

class Page{
    protected $Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $url;
        kernel\Kernel::$PAGE = $url;    // для 404
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
            case 'Wpath' : return $this->getMyWpath();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url=''){
        return new self($url);
    }
    
    // --- --- --- --- --- --- --- ---
    static function reload(){
        if(kernel\Kernel::$REWRITE) $Page = kernel\Kernel::$PAGE;
        else $Page = '/?page='. kernel\Kernel::$PAGE;
        
        header('Location: '. kernel\Kernel::$WHOME .'/'. $Page);
        exit();
    }
}
?>