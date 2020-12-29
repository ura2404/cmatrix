<?php
/**
 * Class Cmatrix\Web\Ide\Resource\Js
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-29
 */

namespace Cmatrix\Web\Ide\Resource;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Js extends web\Ide\Resource {
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- --- ---    
    protected function createCache(){
        $Content = file_get_contents($this->Path);
        $Content = preg_replace("/\/\/.*\n/", '', $Content);                  // однострочные коментарии
        $Content = preg_replace("/\/\*(.*?)\*\//sm", '', $Content);           // многострочные коментарии
        $Content = mb_ereg_replace('[ ]+', ' ', $Content);                    // двойные пробелы
        $Content = preg_replace("/[\r\n|\n|\t]/", '', $Content);              // переносы строк
        $Content = preg_replace("/\s+([{|}|)|;|,|:|=]+)/", '\\1',$Content);   // лишние символы до
        $Content = preg_replace("/([{|}|)|;|,|:|=]+)\s+/", '\\1',$Content);   // лишние символы после
        
        $Cache = web\Ide\Cache::get('res');
        $Cache->updateValue($this->CacheKey,$Content);
    }
    
    // --- --- --- --- --- --- --- ---    
    protected function getMyHtml(){
        return '<script type="text/javascript" src="' .$this->Link. '"></script>';
    }
}
?>