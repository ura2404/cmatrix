<?php
/**
 * Class Module
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Module extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    protected $Path;
    
    protected $_Wpath;
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $this->getMyUrl($url);
        $this->Path = $this->getMyPath($this->Url);
        
        parent::__construct($url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->Path;
            case 'Wpath' : return $this->getMyWpath();
            case 'FormsList' : return $this->getMyFormsList();
            case 'ResourcesList' : return $this->getMyResourcesList();
            case 'DatamodelsList' : return $this->getMyDatamodelsList();
            case 'createCache' : return $this->createMyCache();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyUrl($url){
        return strBefore($url,'/');
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath($url){
        $Path = kernel\Kernel::$HOME .'/module/'. $url;
        if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error($this,'module [' .$url. '] is not exists.');
        
        return $Path;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyWpath(){
        return $this->getInstanceValue('_Wpath',function(){
            return kernel\Kernel::$WHOME .'/module/'. $this->Url;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createMyCache(){
        // forms --- --- ---
        array_map(function($value){
            ide\Form::cache($this->Url .'/'. $value);
        },$this->FormsList);
        
        // resource --- --- ---
        //$_resource($this->Path .'/res');
        
        // datamodel --- --- ---
        array_map(function($value){
            ide\Datamodel::cache($this->Url .'/'. $value);
        },$this->DatamodelsList);
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyFormsList(){
        $Root = $this->Path .'/form';
        
        $_rec = function($root=null,&$arr=[]) use($Root,&$_rec){
            $Files = array_diff(scandir($Root .'/'. $root),['.','..']);
            $Files = array_filter($Files,function($value) use($Root,$root){
                $Path = $Root .'/'. $root .'/'. $value;
                return is_dir($Path) && $value{0} !== '_' ? true : false;
            });
            
            array_map(function($value) use($Root,$root,&$_rec,&$arr){
                $Path = ltrim($root .'/'. $value,'/');
                if(file_exists($Root.'/'.$Path.'/form.json')) $arr[] = $Path;
                $_rec($Path,$arr);
            },$Files);
            
            return $arr;
        };
        
        return file_exists($Root) && is_dir($Root) ? $_rec() : [];
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyResourcesList(){
        return [];
    }

    // --- --- --- --- --- --- --- ---
    private function getMyDatamodelsList(){
        $Root = $this->Path .'/dm';
        
        $_classes = function($root) use($Root){
            $Files = array_diff(scandir($Root .'/'. $root),['.','..']);
            $Files = array_filter($Files,function($value) use($Root,$root){
                $Path = $Root .'/'. $root .'/'. $value;
                return is_dir($Path) || strpos($value,'.class.php') === false || !filesize($Path) ? false : true;
            });
            $Files = array_map(function($value) use($root){
                return $root .'/'. substr($value,0,strpos($value,'.'));
            },$Files);
            return $Files;
        };
        
        $_rec = function($root=null,&$arr=[]) use($Root,$_classes,&$_rec){
            $Files = array_diff(scandir($Root .'/'. $root),['.','..']);
            $Files = array_filter($Files,function($value) use($Root,$root){
                $Path = $Root .'/'. $root .'/'. $value;
                return is_dir($Path) && $value{0} !== '_' ? true : false;
            });
            array_map(function($value) use($Root,$root,$_classes,&$_rec,&$arr){
                $Path = ltrim($root .'/'. $value,'/');
                $Arr = $_classes($Path);
                $arr = array_merge($arr,$Arr);
                $_rec($Path,$arr);
            },$Files);
            
            return $arr;
        };
        
        return file_exists($Root) && is_dir($Root) ? $_rec() : [];
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
    
    // --- --- --- --- --- --- --- ---
    static function cache($url){
        self::get($url)->createCache;
    }
    
    /* 
    // --- --- --- --- --- --- --- ---
    static function createCacheForms(){
        $Root = kernel\Kernel::$HOME .'/module';
        $Files = array_diff(scandir($Root),['.','..']);
        $Files = array_filter($Files,function($value) use($Root){
            $Path = $Root .'/'. $value;
            return is_dir($Path) && $value{0} !== '_' ? true : false;
        });
        
        array_map(function($value){
            (new self($value))->createCacheForms;
        },$Files);
    }
    // --- --- --- --- --- --- --- ---
    static function createCacheResources(){
        
    }
    
    // --- --- --- --- --- --- --- ---
    static function createCacheAll(){
        self::createCacheForms();
        self::createCacheResources();
    }
    */
}
?>