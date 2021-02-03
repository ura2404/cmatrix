<?php
/**
 * Class Cmatrix\Kernel\Ide\Datamodel
 * 
 * Управление моделью данных.
 * Если нужно получить любую информацию о данных, то это сюда.
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-03
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\App as app;

class Datamodel extends kernel\Reflection implements iDatamodel,iModel {
    protected $Url;
    
    protected $_ClassName;
    protected $_Json;
    protected $_Parent;
    protected $_Props;
    protected $_OwnProps;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
        
        $this->isExists();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Url' : return $this->Url;
            case 'ClassName'  : return $this->getMyClassName();
            case 'Json'       : return $this->getMyJson();
            case 'Parent'     : return $this->getMyParent();
            case 'Props'      : return $this->getMyProps();
            case 'OwnProps'   : return $this->getMyOwnProps();
            case 'Init'       : return $this->getMyInit();
            case 'Indexes'    : return $this->getMyIndexes();
            case 'OwnIndexes' : return $this->getMyOwnIndexes();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function isExists(){
        $Path = kernel\Ide\Part::get($this->Url)->Path.CM_DS.'dm'.CM_DS.kernel\Url::get($this->Url)->Part3.'.class.php';
        if(!file_exists($Path) || is_dir($Path)) throw new ex\Error('Datamodel "'. $this->Url .'" is not exists.');
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function setByLang($val,$lang='_def'){
        if(!is_array($val)) return $val;
        return array_key_exists($lang,$val) ? $val[$lang] : null;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * Функция getMyClassName()
     * Получить имя класса модели данных.
     * 
     * @return string - datamodel class name
     */
    protected function getMyClassName(){
        return $this->getInstanceValue('_ClassName',function(){
            return kernel\Url::get($this->Url)->Part1.'\\'.kernel\Url::get($this->Url)->Part2.'\\Dm\\'.kernel\Url::get($this->Url)->Part3;
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return array - datamodel json config
     */
    protected function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            $Path = kernel\Ide\Part::get($this->Url)->Path.CM_DS.'dm'.CM_DS.kernel\Url::get($this->Url)->Path.'.dm.json';
            if(!file_exists($Path) || is_dir($Path)) throw new ex\Error('Datamodel description json file "'. $this->Url .'" is not exists.');
            return kernel\Json::decode(file_get_contents($Path))['dm'];
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * Функция getMyParent()
     * Получить экземпляр сущности родительской модели данных.
     * 
     * @return Cmatrix\Kernel\Ide\Datamodel - parent datamodel
     */
    protected function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            //return $this->Json['parent'];
            $ParentUrl = $this->Json['parent'];
            return $ParentUrl ? self::get($ParentUrl) : null;
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * Получение собственных свойств, определённых в датамодели
     */
    protected function getMyOwnProps(){
        return $this->getInstanceValue('_OwnProps',function(){
            // 1. если нет Url, то это не datamodel, а класс родитель Cmatrix/Ide/Datamodel
            //if(!$this->Url) return [];
            
            // 2. определить текущий язык
            $Lang = app\Kernel::get()->Config->getValue('lang','_def');
            
            // 3. получить свойства
            // - отсеить свойства с преффиксом '_'
            // - парсить язык в мультиязыковых полях
            $Props = $this->Json['props'];
            foreach($Props as $code=>$prop){
                if(substr($code,0,1) === '_') unset($Props[$code]);
                else{
                    $prop['name']   = $this->setByLang($prop['name'],$Lang);
                    $prop['label']  = $this->setByLang($prop['label'],$Lang);
                    $prop['baloon'] = $this->setByLang($prop['baloon'],$Lang);
                    
                    if(!$prop['name']) $prop['name'] = $prop['code'];
                    if(!$prop['label']) $prop['label'] = $prop['name'];
                    
                    $Props[$code] = $prop;
                }
            }
            return $Props;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * Получение всех свойств с наследованием
     */
    //protected function getMyAllProps(){
    protected function getMyProps(){
        return $this->getInstanceValue('_Props',function(){
            
            // 1. Получить собственный свойства
            $Props = $this->OwnProps;
            
            // 2. Получить свойства родителя
            //$ParentProps = self::get($this->Parent)->OwnProps;
            $ParentProps = $this->Parent ? $this->Parent->OwnProps : [];
            
            // 3. Склеить свойства родителя и собственные
            $Props = array_merge($ParentProps,$Props);
            
            // 4. Сортировать поля
            if(isset($Props['id'])) $Arr = [ 'id' => $Props['id'] ]; unset($Props['id']);
            if(isset($Props['info'])) { $Info = $Props['info']; unset($Props['info']); } else $Info = null;
            if(isset($Props['systype'])) { $Systype = $Props['systype']; unset($Props['systype']); } else $Systype = null;
            
            array_map(function($code,$prop) use(&$Arr){
                $Arr[$code] = $prop;
            },array_keys($Props),array_values($Props));
            
            $Info ? $Arr['info'] = $Info : null;
            $Systype ? $Arr['systype'] = $Systype : null;
            $Props = $Arr;

            return $Props;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyInit(){
        $Init = $this->Json['init'];
        return is_array($Init) ? $Init : [];
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - массив массивов собственных свойтсв, требующих индексов
     * [
     *    [
     *        '<prop_code>" : [],
     *        '<prop_code>" : [],
     *    ],
     *    [
     *        '<prop_code>" : [],
     *        '<prop_code>" : [],
     *    ]
     * ]
     */
    protected function getMyOwnIndexes(){
        $Indexes = $this->Json['indexes'];
        
        return array_map(function($group){
            return array_map(function($prop){
                return $this->getProp($prop);
            },$group);
        },is_array($Indexes) ? $Indexes : []);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - массив массивов свойтсв c наследование, требующих индексов
     */
    protected function getMyIndexes(){
        $Indexes = $this->OwnIndexes;
        $ParentIndexes = $this->Parent ? $this->Parent->OwnIndexes : [];
        return array_merge($ParentIndexes,$Indexes);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function isPropExists($propCode){
        return array_key_exists($propCode,$this->Props);
    }

    // --- --- --- --- --- --- --- ---
    public function getProp($propCode){
        if(!$this->isPropExists($propCode)) throw new ex\Error('Datamodel "' .$this->Url. '" prop "' .$propCode. '"is not exists.');
        return $this->Props[$propCode];
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>