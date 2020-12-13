<?php
/**
 * Class Mvc
 * 
 * @author ura@itx.su
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Mvc {
    private $Form;

    /**
     * Form url, for example /MyProject/MyPart/site/about.
     */
    //private $Url;
    
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
    function __construct(\Cmatrix\Web\Ide\Form $form){
        $this->Form = $form;
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
        $Type = $this->Form->Type;
        
        if(!isset(self::$CONTROLLES[$Type])) throw new ex\Error('controller class [' .$Type. '] is not defined.');
        if(!isset(self::$VIEWS[$Type]))      throw new ex\Error('view class [' .$Type. '] is not defined.');
        if(!isset(self::$MODELS[$Type]))     throw new ex\Error('modell class [' .$Type. '] is not defined.');
        
        $ControllerClass = self::$CONTROLLES[$Type];
        $ViewClass       = self::$VIEWS[$Type];
        $ModelClass      = self::$MODELS[$Type];
        
        return new $ControllerClass(new $ViewClass($this->Form), new $ModelClass($this->Form));
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyHtml(){
        return $this->Controller->Data;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(\Cmatrix\Web\Ide\Form $form){
        return new self($form);
    }
}
?>