<?php
/**
 * Class Props
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-28
 * 
 * types
 *   - ::id::
 *   - ::pid:: - parent id
 *   - ::hid::
 *   - ::act:: - active
 *   - ::dlt:: - deleted
 *   - ::hdn:: - hidden
 * 
 *   - ::ip::       - ip addres // v4 - 15 символов // v6 - 39 символов
 *   - ::datetime:: - timestamp 
 *   - ::string::   - varchar
 *   - ::txt::      - text
 *   - ::real::     - real
 *   - ::integer::  - integer
 * 
 *  defaults
 *   - ::counter::
 *   - ::hid::
 */

namespace Cmatrix\Kernel\Ide\Generator\Datamodel;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Prop {
    private $Code;
    private $Type;
    private $Order;
    
    private $Name;
    
    private $Def;
    
    private $Own = true;
    /*
    static $PROP = [
        'code' => null,
        'type' => null,
        
        'name' => null,
        'label' => null,
        'baloon' => null,
        
        'length' => null,
        'nn' => null,
        'index' => null,
        'default' => null,
        'info' => null,
        'association' => null,
        
        'own' => null,
        'registr' => null,
    ];
    */
    static $PROP = [
        'code' => null,
        'type' => null,
        
        'name'   => null,
        'label'  => null,
        'baloon' => null,
        
        'length'  => null,
        'nn'      => null,
        'index'   => null,
        'default' => null,
        'info'    => null,
        
        'association' => null,
        
        'own'     => null,
        'registr' => null,
    ];
    
    static $TYPES = [
        '::id::'       => [ 'type' => '::id::',   'nn' => true,                'default' => '::counter::', ],
        '::pid::'      => [                                                                                ],
        '::hid::'      => [                       'nn' => true,                'default' => '::hid::',     ],
        //'::act::'      => [ 'type' => 'bool',                                                              ],
        //'::dlt::'      => [ 'type' => 'bool',                                                              ],
        //'::hdn::'      => [ 'type' => 'bool',                                                              ],
        
        //'::ip::'       => [                                     'length' =>39,                             ],
        //'::string::'   => [ 'type' => 'varchar',                                                           ],
        '::datetime::' => [ 'type' => 'timestamp',                                                         ],
        //'::txt::'      => [ 'type' => 'text',                                                              ],
    ];
    
    // --- --- --- --- --- --- --- ---
    function __construct($code,$type='::string::',$order=0){
        $this->Code = $code;
        $this->Type = $type;
        $this->Order = $order;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Code' : return $this->Code;
            case 'Data' : return $this->getMyData();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyData(){
        $Data = [
            'code' => $this->Code,
            'type' => $this->Type,
            
            'default' => $this->Def,
            
            'own' => $this->Own,
            'order' => $this->Order,
        ];
        //return $Data;
        
        if(array_key_exists($this->Type,self::$TYPES)) $Data = array_merge($Data,self::$TYPES[$this->Type]);
        
        return array_merge(self::$PROP,$Data);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function setDefault($value){
        $this->Def = $value;
        return $this;
    }

    // --- --- --- --- --- --- --- ---
    public function setOwn($value){
        $this->Own = $value;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($code,$type='::string::',$order=0){
        return new self($code,$type,$order);
    }
}