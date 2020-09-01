<?php
/**
 * Class Session
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-18
 */
 
namespace Cmatrix\Datamodel\Core;
use \Cmatrix as cm;
//use \Cmatrix\Orm as orm;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Ide\Generator\Datamodel as generator;

class Entity extends ide\Datamodel{
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct();
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyName(){
        return 'Сущность';
    }

    // --- --- --- --- --- --- ---
    protected function setMyProps(){
        return (generator\Props::get()
            //->setProps(parent::setMyProps(),true)
            ->setProps(parent::setMyProps())
            
            ->setProp(generator\Prop::get('id','::id::',-1))
            ->setProp(generator\Prop::get('info','::txt::',999))
        );
    }    

    // --- --- --- --- --- --- --- ---
    /*protected function createJson(){
        return [
            'code' => 'Cmatrix/Core/Entity',
            'props' => [
                'id' => [],
                'parent_id' => [],
                'chain_id' => [],
                'status' => [],
                'active' => [],
                'hidden' => [],
                'deleted' => [],
                'info' => [],
            ]
        ];
    }*/
    
}
?>