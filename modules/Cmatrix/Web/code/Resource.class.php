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

class Resource extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_isRaw;
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
            case 'isRaw' : return $this->getMyRaw();
            case 'Path'  : return $this->getMyPath();
            case 'Link'  : return $this->getMyLink();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyRaw(){
        return $this->getInstanceValue('_isRaw',function(){
            return strStart($this->Url,'raw::') ? true : false;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = \Cmatrix\Web\Kernel::get()->Home .($this->isRaw ? '/res/'. strAfter($this->Url,'raw::') : '/cache/'. Ide\Resource::get($this->Url)->CacheName);
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyLink(){
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        $Cl = '\Cmatrix\Web\Resource\\' . ucfirst(Ide\Resource::get($url)->Type);
        return $Cl::get($url);
    }
}
?>