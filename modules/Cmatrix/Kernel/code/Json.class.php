<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Kernel;
use \Cmatrix\Kernel\Exception as ex;

class Json {
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function decode($data){
        return json_decode($data,true,
            JSON_PRETTY_PRINT             // форматирование пробелами
            | JSON_UNESCAPED_SLASHES      // не экранировать /
            | JSON_UNESCAPED_UNICODE      // не кодировать текст
        );
    }

    // --- --- --- --- --- --- --- ---
    static function encode($data){
        return json_encode($data,
            JSON_PRETTY_PRINT             // форматирование пробелами
            | JSON_UNESCAPED_SLASHES      // не экранировать /
            | JSON_UNESCAPED_UNICODE      // не кодировать текст
        );
    }
}
?>