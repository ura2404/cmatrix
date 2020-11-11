<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web\Ide as ide;
use \Cmatrix\Web\Mvc as mvc;

class Page extends kernel\Reflection{
    static $INSTANCES = [];
    /**
     * url страницы, переданный в браузер
     */
    protected $Url;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        //kernel\Kernel::$PAGE = $url;    // для 404
        parent::__construct($this->Url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
            case 'Path' : return $this->getMyPath();
            default : throw new ex\Error($this,'class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        $Config = kernel\Config::get('www/config.json');
        
        $PageUrl = $this->Url === '' ? $Config->getValue('pages/def') : $Config->getValue('pages/aliases/'. $this->Url);
        
        // 404
        if(!$PageUrl) $PageUrl = $Config->getValue('pages/aliases/404');
        if(!$PageUrl) throw new ex\Error($this,'page "404" is not defined.');
        
        $FormUrl = ide\Page::get($PageUrl)->Form;
        
        $Html = mvc\Mvc::get($FormUrl)->Html;
        return $Html;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        $Config = kernel\Config::get('/www/config.json');
        
        $Url = $Config->getValue('pages/aliases/'. $this->Url);
        if(!$Url) throw new ex\Error($this,'page "'.$this->Url.'" is not defined.');
        
        return \Cmatrix\Web\Kernel::get()->Home.'/'.$this->Url;
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