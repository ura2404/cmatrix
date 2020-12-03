<?php
/**
 * Class Ico
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-03
 */

namespace Cmatrix\Web\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Ico extends web\Resource{
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
    static function get($url){
        return new self($url);
    }
}
?>