<?php
/**
 * Class Cache
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Cache extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $url;
        
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = kernel\Kernel::$HOME .'/.cache/'.$this->Url;
            if(!file_exists($Path)){
                mkdir($Path,0770,true);
                chown($Path,'www-data');
                chgrp($Path,'www-data');
            }
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function clear(){
        $_rec = function($path) use(&$_rec){
            $Files = array_diff(scandir($path),['.','..']);
            array_map(function($value) use($path,&$_rec){
                $Path = $path .'/'. $value;
                is_dir($Path) ? $_rec($Path) : unlink($Path);
            },$Files);
        };
        
        $_rec($this->Path);
    }

    // --- --- --- --- --- --- --- ---
    public function put($key,$value){
        $Path = $this->Path .'/'. $key;

        try{
            file_put_contents($Path,$value);
            chmod($Path,0660);
            chown($Path,'www-data');
            chgrp($Path,'www-data');
        }
        catch(\Exception $e){
            
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
    
}

?>