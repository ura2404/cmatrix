<?php
/**
 * Class Css
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-03
 */

namespace Cmatrix\Web\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Css extends web\Resource{
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
            return '<link rel="stylesheet" media="none" type="text/css" href="'. $this->Path .'" onload="if(media!=\'all\')media=\'all\'"/>';
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