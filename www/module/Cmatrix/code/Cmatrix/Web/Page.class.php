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

class Page{

    public $Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        $Pages = cm\Kernel\Config::get('pages');
        
        $PageUrl = $this->Url === '/' ? $Pages->getValue('def') : $Pages->getValue($this->Url);
        $FormUrl = ide\Page::get($PageUrl)->Form;
        
        $Html = mvc\Mvc::get($FormUrl)->Html;
        return $Html;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        $Url = $url === null ? '500': ($url === '' ? '/' : $url);
        return new self($Url);
    }
}

?>