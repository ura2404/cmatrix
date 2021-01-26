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
    
    //static $INSTANCES = [];
    /**
     * url страницы, переданный в браузер
     */
    protected $Url;
    protected $StaticContent;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url=''){
        $this->Url = $url;
        parent::__construct('page.'.$this->Url);
        
        //dump(self::$REFINSTANCES);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html'   : return $this->getMyHtml();
            case 'Path'   : return $this->getMyPath();
            default : return parent::__get($name);
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
        $Config = \Cmatrix\Web\Kernel::get()->Config;
        
        // 1. найти url в конфиге
        $PageUrl = $this->Url === '' ? $Config->getValue('web/pages/def') : $Config->getValue('web/pages/aliases/'. $this->Url);
        
        try{
            // 2. если нет url в конфиге, то найти url 404-ой страницв 
            //404
            if(!$PageUrl) $PageUrl = $Config->getValue('web/pages/aliases/404');
            if(!$PageUrl) throw new ex\Error('page "404" is not defined.');
            
            //3. вывести html
            $Form = web\Ide\Page::get($PageUrl)->Form;
            //dump($Form);
            
            $Html = web\Mvc\Mvc::get($Form)->Html;
            
            //4. модификация
            if(CM_MODE === 'development' && strpos($Html,'<head>') !== false && strpos($Html,'</head>') !== false){
                $_js = function(){
                    //$F = 'var _cmfp=function(u){var a=u.split("/");var p=a.reverse().pop();var s=a.reverse().join("/");return ' ."'".Ide\Form::get("'+p+'/'+s")->Path. ';};';
                    //$R = 'var _cmrp=function(u){var a=u.split("/");var p=a.reverse().pop();var s=a.reverse().join("/");return ' ."'".Ide\Resource::get("'+p+'/'+s")->Path. ';};';
                    $P = 'var _cmpp=function(u){u=u===undefined?"":u;return '."'" . web\Kernel::get()->Home . '/' . "'+u;}";
                    //self::get("'+u")->Path .';}';
                        
                    //return $F.$R.$P;
                    return $P;
                };
                $Arr = [];
                $Arr[] = strBefore($Html,'<head>',true);
                $Arr[] = '<script type="text/javascript">' .$_js(). '</script>';
                $Arr[] = strAfter($Html,'<head>');
                $Html = implode('',$Arr);
                
                // добавить less, если нужно
                if(strpos($Html,'stylesheet/less') !==false){
                    
                    // !!! версия less
                    $LessVersion = '3.7.1';
                    
                    $Arr = explode('</head>',$Html);
                    $Arr[0] .= web\Ide\Resource::get('Cmatrix/Vendor/lesscss/'.$LessVersion.'/less.min.js')->Html;
                    //$Arr[0] .= '<script type="text/javascript" src="' .\cmWeb\Ide\Resource::get('vendor/less')->Path. '/'. $LessVersion .'/less.min.js"></script>';
                    $Html = implode('</head>',$Arr);
                }
            }
            
            //5. вывести html
            //dump($Html);
            return $Html;
        }
        // 4. если что-то пошло не так, вывести страницу exception, если она есть, если её нет, то просто вывести текст ошибки в браузерa
        catch(ex\Error $e){
            //dump($e->getMessage());
            //return;
            
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
            
            $PageUrl = $Config->getValue('web/pages/aliases/404');
            if($PageUrl) return $_web($PageUrl);
            else return $_noweb();
        }
        catch(\Throwable $e){
            return \Cmatrix\Kernel\Exception::createMessage($e);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        $Config = \Cmatrix\Web\Kernel::get()->Config;
        
        $Url = $Config->getValue('web/pages/aliases/'. $this->Url);
        //if(!$Url && $this->Url!='') throw new ex\Error('page "'.$this->Url.'" is not defined.');
        
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