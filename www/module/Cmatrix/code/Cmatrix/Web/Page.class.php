<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Mvc as mvc;

class Page {
    protected $_Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->_Url = $url;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
            case 'Url' : return $this->getMyUrl();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        $Pages = cm\Kernel\Config::get('pages');
        
        $PageUrl = $this->_Url === '' ? $Pages->getValue('def') : $Pages->getValue('pages/'. $this->_Url);
        
        // 404
        //if(!$PageUrl) throw new ex\Error($this,'page [' .$this->_Url. '] is not defined.');
        if(!$PageUrl) $PageUrl = $Pages->getValue('pages/404');
        if(!$PageUrl) throw new ex\Error($this,'page 404 is not definred.');
        
        $FormUrl = ide\Page::get($PageUrl)->Form;
        
        $Html = mvc\Mvc::get($FormUrl)->Html;
        return $Html;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyUrl(){
        return kernel\Kernel::$WHOME.'/'.$this->_Url;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url=''){
        return new self($url);
    }
}

?>