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
            default : throw new ex\Property($this,$name);
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
            return \Cmatrix\Web\Kernel::get()->Home .($this->isRaw ? '/res/'. web\Ide\Resource::get($this->Url)->Path: '/cache/'. web\Ide\Resource::get($this->Url)->CacheName);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyLink(){
        return $this->getInstanceValue('_Link',function(){
            $_funs = [
                'css' => function(){
                    return '<link rel="stylesheet" media="none" type="text/css" href="'. $this->Path .'" onload="if(media!=\'all\')media=\'all\'"/>';
                },
                'js' => function(){
                    return '<script type="text/javascript" src="' .$this->Path. '"></script>';
                },
            ];
            
            $Type = web\Ide\Resource::get($this->Url)->Type;
            
            if(!array_key_exists($Type,$_funs)) throw new ex\Error('create link function for resource "' .$this->Url. '" is not defined.');
            return $_funs[$Type]();
        });
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>