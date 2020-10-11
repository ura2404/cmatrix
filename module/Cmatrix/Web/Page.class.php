<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web;
//use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
//use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;
//use \Cmatrix\Mvc as mvc;

class Page{
    /**
     * url страницы, переданный в браузер
     */
    protected $Url;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        //kernel\Kernel::get();
        
        $this->Url = $url;
        //kernel\Kernel::$PAGE = $url;    // для 404
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
    private function getMyHtml(){
        $Config = kernel\Config::get('/www/config.json');
        
        $Url = $this->Url === '' ? $Config->getValue('def') : $Config->getValue('pages/'. $this->Url);
        
        // 404
        if(!$Url) $Url = $Config->getValue('pages/404');
        if(!$Url) throw new ex\Error($this,'page 404 is not defined.');
        
        dump($Url);
        
        /*
        $Pages = cm\Kernel\Config::get('pages');
        
        $PageUrl = $this->Url === '' ? $Pages->getValue('def') : $Pages->getValue('pages/'. $this->Url);
        
        // 404
        if(!$PageUrl) $PageUrl = $Pages->getValue('pages/404');
        if(!$PageUrl) throw new ex\Error($this,'page 404 is not defined.');
        
        $FormUrl = ide\Page::get($PageUrl)->Form;
        
        $Html = mvc\Mvc::get($FormUrl)->Html;
        return $Html;
        */
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url=''){
        return new self($url);
    }
    
    // --- --- --- --- --- --- --- ---
    /*
    static function reload(){
        if(kernel\Kernel::$REWRITE) $Page = kernel\Kernel::$PAGE;
        else $Page = '/?page='. kernel\Kernel::$PAGE;
        
        header('Location: '. kernel\Kernel::$WHOME .'/'. $Page);
        exit();
    }
    */
}
?>