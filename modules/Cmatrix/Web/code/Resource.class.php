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
    protected $_CacheName;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        
        parent::__construct($this->Url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Link' : return $this->getMyLink();
            default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return \Cmatrix\Web\Kernel::get()->Home .'/res/'. web\Ide\Resource::get($this->Url)->CacheName;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyLink(){
        $_funs = [
            'css' => function(){
                return '<link rel="stylesheet" media="none" type="text/css" href="'. web\Ide\Resource::get($this->Url)->Path .'" onload="if(media!=\'all\')media=\'all\'"/>';
            },
        ];
        
        $Type = web\Ide\Resource::get($this->Url)->Type;
        
        if(!array_key_exists($Type,$_funs)) new ex\Error('create link function for resource "' .$this->Url. '" is not defined.');
        return $_funs[$Type]();
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>