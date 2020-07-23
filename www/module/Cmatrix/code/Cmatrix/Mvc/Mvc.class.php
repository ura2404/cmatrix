<?php
/**
 * Class Mvc
 * 
 * @author ura@itx.su
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Mvc;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Mvc {

    /**
     * Form url, for example /site/about.
     */
    private $Url;
    
    /**
    */
    private $Controller;
    
    /**
     */
    private static $CONTROLLES = [
        'html' => '\Cmatrix\Mvc\Controller\Html',
        'php' => '\Cmatrix\Mvc\Controller\Php',
        'element' => '\Cmatrix\Mvc\Controller\Element',
        'twig' => '\Cmatrix\Mvc\Controller\Twig',
    ];
    private static $VIEWS = [
        'html' => '\Cmatrix\Mvc\View\Html',
        'php' => '\Cmatrix\Mvc\View\Php',
        'element' => '\Cmatrix\Mvc\View\Element',
        'twig' => '\Cmatrix\Mvc\View\Twig',
    ];
    private static $MODELS = [
        'html' => '\Cmatrix\Mvc\Model\Html',
        'php' => '\Cmatrix\Mvc\Model\Php',
        'element' => '\Cmatrix\Mvc\Model\Element',
        'twig' => '\Cmatrix\Mvc\Model\Twig',
    ];

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        $this->Controller = $this->getMyController();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->getMyHtml();
        }
    }


    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyController(){
        $Type = cm\Kernel\Ide\Form::get($this->Url)->Type;
        
        if(!isset(self::$CONTROLLES[$Type])) throw new ex\Error($this,'controller class [' .$Type. '] is not defined.');
        if(!isset(self::$VIEWS[$Type])) throw new ex\Error($this,'view class [' .$Type. '] is not defined.');
        if(!isset(self::$MODELS[$Type])) throw new ex\Error($this,'modell class [' .$Type. '] is not defined.');
        
        $ControllerClass = self::$CONTROLLES[$Type];
        $ViewClass = self::$VIEWS[$Type];
        $ModelClass = self::$MODELS[$Type];
        
        return new $ControllerClass(new $ViewClass($this->Url), new $ModelClass($this->Url));
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        return $this->Controller->Data;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}

?>