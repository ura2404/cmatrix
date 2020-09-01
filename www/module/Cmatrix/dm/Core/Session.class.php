<?php
/**
 * Class Session
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-14
 */

namespace Cmatrix\Datamodel\Core;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Ide\Generator\Datamodel as generator;

class Session extends Entity{
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct();
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyName(){
        return 'Сессия';
    }
    
    // --- --- --- --- --- --- ---
    protected function setMyProps(){
        return (generator\Props::get()
            ->setProps(parent::setMyProps())
            
            ->setProp(generator\Prop::get('id','::id::',-1))
            
            ->setProp(generator\Prop::get('ip4','::ip::'))
            ->setProp(generator\Prop::get('ip4x','::ip::'))
            ->setProp(generator\Prop::get('proxy'))
            ->setProp(generator\Prop::get('agent'))
            ->setProp(generator\Prop::get('lang'))
            ->setProp(generator\Prop::get('charset'))
            ->setProp(generator\Prop::get('encoding'))
            
            ->setProp(generator\Prop::get('info','::txt::',999))
        );
    }    
    
    // --- --- --- --- --- --- --- ---
    /*protected function createJson(){
        dump('session create json');
        
        return [
            'code' => $this->getMyUrl(),
            'name' => $this->getMyName(),
            'parent' => $this->getMyParentUrl(),
            'props' => [
                'ip' => [],
            ]
        ];
        
    }*/
}
?>