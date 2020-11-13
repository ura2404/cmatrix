<?php
/**
 * Class Mvc
 * 
 * @author ura@itx.su
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web\Ide as ide;

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
        'html'    => '\Cmatrix\Web\Mvc\Controller\Html',
        'php'     => '\Cmatrix\Web\Mvc\Controller\Php',
        'element' => '\Cmatrix\Web\Mvc\Controller\Element',
        'twig'    => '\Cmatrix\Web\Mvc\Controller\Twig',
    ];
    private static $VIEWS = [
        'html'    => '\Cmatrix\Web\Mvc\View\Html',
        'php'     => '\Cmatrix\Web\Mvc\View\Php',
        'element' => '\Cmatrix\Web\Mvc\View\Element',
        'twig'    => '\Cmatrix\Web\Mvc\View\Twig',
    ];
    private static $MODELS = [
        'html'    => '\Cmatrix\Web\Mvc\Model\Html',
        'php'     => '\Cmatrix\Web\Mvc\Model\Php',
        'element' => '\Cmatrix\Web\Mvc\Model\Element',
        'twig'    => '\Cmatrix\Web\Mvc\Model\Twig',
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
        $Type = ide\Form::get($this->Url)->Type;
        
        if(!isset(self::$CONTROLLES[$Type])) throw new ex\Error('controller class [' .$Type. '] is not defined.');
        if(!isset(self::$VIEWS[$Type]))      throw new ex\Error('view class [' .$Type. '] is not defined.');
        if(!isset(self::$MODELS[$Type]))     throw new ex\Error('modell class [' .$Type. '] is not defined.');
        
        $ControllerClass = self::$CONTROLLES[$Type];
        $ViewClass       = self::$VIEWS[$Type];
        $ModelClass      = self::$MODELS[$Type];
        
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