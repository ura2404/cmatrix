<?php
/**
 * Class Js
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-03
 */

namespace Cmatrix\Web\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Js extends web\Resource{
    static $INSTANCES = [];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyLink(){
        return $this->getInstanceValue('_Link',function(){
            return '<script type="text/javascript" src="' .$this->Path. '"></script>';
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