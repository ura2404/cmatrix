<?php
/**
 * Class Resource
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Resource extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($this->Url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        $Path = CM_ROOT.CM_DS.'modules'.CM_DS . $this->Url;
        if(!file_exists($Path)) throw new ex\Error($this,'resource "'.$this->Url.'" is not defined.');
        
        $Pos = strrpos($this->Url,'.');
        $Name = substr($this->Url,0,$Pos);
        $Ext = substr($this->Url,$Pos+1);
        
        $CachName = md5($Name) .'.'. $Ext;
        
        if(CM_MODE === 'development'){
            $Cache = web\Ide\Cache::get('res');
            if(!$Cache->isExists($CachName)) $Cache->copy($CachName,$Path);
        }
        
        return \Cmatrix\Web\Kernel::get()->Home .'/res/'. $CachName;
        
        dump($Name);
        dump($Ext);
        
        
        
        dump(basename($this->Url));
        
        
        dump($Path);
        $info = new \SplFileInfo($Path);
        dump($info->getExtension());
        
        $Hash = md5($this->Url);
        
        return \Cmatrix\Web\Kernel::get()->Home .'/res/'. $Hash;
        
        
        $Config = kernel\Config::get('/www/config.json');
        
        $Url = $Config->getValue('pages/aliases/'. $this->Url);
        if(!$Url) throw new ex\Error($this,'page "'.$this->Url.'" is not defined.');
        
        return $this->Root.'/'.$this->Url;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>