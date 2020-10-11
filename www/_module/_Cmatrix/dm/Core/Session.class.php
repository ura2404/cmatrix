<?php
/**
 * Class Session
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-14
 */

namespace Cmatrix\Datamodel\Core;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
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
    
    // --- --- --- --- --- --- ---
    protected function getMyIdent(){
        if(kernel\Kernel::$SAPI === 'CLI'){
            return [
                'ip4'   => '0.0.0.0',
                'agent' => 'Cmatrix local worker'
            ];
            
        }
        elseif(kernel\Kernel::$SAPI === 'APACHE'){
            $Arr = [
                'ip4'      => isset($_SERVER['REMOTE_ADDR'])          ? $_SERVER['REMOTE_ADDR'] : null,
                'ip4x'     => isset($_SERVER['HTTP_X_FORWARDER_FOR']) ? $_SERVER['HTTP_X_FORWARDER_FOR'] : null,
                'proxy'    => isset($_SERVER['HTTP_VIA'])             ? $_SERVER['HTTP_VIA'] : null,
                'agent'    => isset($_SERVER['HTTP_USER_AGENT'])      ? $_SERVER['HTTP_USER_AGENT'] : null,
                'lang'     => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null,
                'charset'  => isset($_SERVER['HTTP_ACCEPT_CHARSET'])  ? $_SERVER['HTTP_ACCEPT_CHARSET'] : null,
                'encoding' => isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : null,
            ];
            
            if($Arr['ip4'] === '::1') $Arr['ip4'] = '127.0.0.1';
            
            return $Arr;
        }
        else return [];
        
        
    }
    
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    // --- --- --- --- --- --- ---
    public function beforeCreate($ob){
        parent::beforeCreate($ob);
        
        $ob->setValues($this->getMyIdent());
    }
}
?>