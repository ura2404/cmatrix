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
use \Cmatrix\Web as web;

class Page extends kernel\Reflection{
    static $PAGE;
    static $EXCEPTION;
    
    static $INSTANCES = [];
    /**
     * url страницы, переданный в браузер
     */
    protected $Url;
    protected $StaticContent;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url=''){
        $this->Url = $url;
        //kernel\Kernel::$PAGE = $url;    // для 404
        parent::__construct($this->Url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
            case 'Path' : return $this->getMyPath();
            default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }

    // --- --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            case 'StaticContent' : 
                $this->StaticContent = $value;
                break;
                
            default : throw new web\Exception('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        if($this->StaticContent) return $this->StaticContent;
        
        self::$PAGE = $this->Url;
        
        // 1. найти url в конфиге
        $Config = kernel\Config::get('Cmatrix/Web/www/config.json');
        $PageUrl = $this->Url === '' ? $Config->getValue('pages/def') : $Config->getValue('pages/aliases/'. $this->Url);
        
        try{
            // 2. если нет url в конфиге, то найти url 404-ой страницв 
            //404
            if(!$PageUrl) $PageUrl = $Config->getValue('pages/aliases/404');
            if(!$PageUrl) throw new ex\Error('page "404" is not defined.');
            
            //3. вывести html
            $FormUrl = web\Ide\Page::get($PageUrl)->Form;
            
            $Html = web\Mvc\Mvc::get($FormUrl)->Html;
            //dump($Html);
            
            return $Html;
        }
        // 4. если что-то пошло не так, вывести страницу exception, если она есть, если её нет, то просто вывести текст ошибки в браузерa
        catch(ex\Error $e){
            
            $_noweb = function() use($e){
                $Message = \Cmatrix\Kernel\Exception::createMessage($e);
                return $Message;
            };
            
            $_web = function($url) use($e,$_noweb){
                try{
                    self::$EXCEPTION = \Cmatrix\Kernel\Exception::createMessage($e);
                    $FormUrl = web\Ide\Page::get($url)->Form;
                    $Html = web\Mvc\Mvc::get($FormUrl)->Html;
                    return $Html;
                }
                catch(ex\Error $e){
                    return $_noweb();
                }
            };
            
            $PageUrl = $Config->getValue('pages/aliases/404');
            if($PageUrl) return $_web($PageUrl);
            else return $_noweb();
        }
        catch(\Throwable $e){
            echo $e->getMessage();
            return \Cmatrix\Kernel\Exception::createMessage($e);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        $Config = kernel\Config::get('Cmatrix/Web/www/config.json');
        
        $Url = $Config->getValue('pages/aliases/'. $this->Url);
        if(!$Url && $this->Url!='') throw new ex\Error('page "'.$this->Url.'" is not defined.');
        
        return web\Kernel::get()->Home.'/'.$this->Url;
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