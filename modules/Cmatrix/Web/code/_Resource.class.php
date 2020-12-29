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

class Resource2 extends kernel\Reflection{
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_Link;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($this->Url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Html' : return $this->getMyHtml();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Resource = Ide\Resource::get($this->Url);
            
            $Path = \Cmatrix\Web\Kernel::get()->Home .
                (
                    $Resource->Src === 'raw' ? 
                    strAfter($Resource->Path,kernel\Ide\Part::get($this->Url)->Path) 
                    : '/cache/'.$Resource->CacheKey
                );
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyHtml(){
        switch(Ide\Resource::get($this->Url)->Type){
            case 'js'   : return '<script type="text/javascript" src="' .$this->Path. '"></script>';
            
            case 'css'  : return '<link rel="stylesheet" media="none" type="text/css" href="'. $this->Path .'" onload="if(media!=\'all\')media=\'all\'"/>';
            //case 'css'  : return '<link rel="stylesheet" type="text/css" href="'. $this->Path .'"/>';
            
            //case 'less' : return '<link rel="stylesheet/less" media="none" type="text/css" href="'. $this->Path .'" onload="if(media!=\'all\')media=\'all\'"/>';
            //case 'less' : return '<link rel="stylesheet/less" type="text/css" href="'. $this->Path .'"/>';
            case 'less' : return '<link rel="stylesheet" type="text/css" href="'. $this->Path .'"/>';
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