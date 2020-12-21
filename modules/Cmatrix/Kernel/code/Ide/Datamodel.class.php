<?php
/**
 * Class Cmatrix\Kernel\Ide\Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-03
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\App as app;

class Datamodel extends kernel\Reflection{
    protected $Url;
    
    protected $_ClassName;
    protected $_Json;
    protected $_Parent;
    protected $_Props;
    protected $_OwnProps;
    //protected $_AllProps;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'ClassName' : return $this->getMyClassName();
            case 'Json'      : return $this->getMyJson();
            case 'Parent'    : return $this->getMyParent();
            case 'Props'     : return $this->getMyProps();
            case 'OwnProps'  : return $this->getMyOwnProps();
            //case 'AllProps'  : return $this->getMyAllProps();
            default : return parent::__get($name);
        }
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
     * @return string - datamodel class name
     */
    protected function getMyClassName(){
        return $this->getInstanceValue('_ClassName',function(){
            return kernel\Url::get($this->Url)->Module.'\\'.kernel\Url::get($this->Url)->Part.'\\Dm\\'.kernel\Url::get($this->Url)->Path;
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            $Path = kernel\Ide\Part::get($this->Url)->Path.CM_DS.'dm'.CM_DS.kernel\Url::get($this->Url)->Path.'.dm.json';
            if(!file_exists($Path) || is_dir($Path)) throw new ex\Error('Datamodel description json file "'. $this->Url .'" is not exists.');
            return kernel\Json::decode(file_get_contents($Path))['dm'];
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            return $this->Json['parent'];
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * Получение собственных свойств, определённых в датамодели
     */
    protected function getMyOwnProps(){
        return $this->getInstanceValue('_OwnProps',function(){
            if(!$this->Url) return [];
            
            $Lang = app\Kernel::get()->Config->getValue('lang','_def');
            
            $Props = $this->Json['props'];
            foreach($Props as $code=>$prop){
                if(substr($code,0,1) === '_') unset($Props[$code]);
                else{
                    $prop['name']   = $this->setByLang($prop['name'],$Lang);
                    $prop['label']  = $this->setByLang($prop['label'],$Lang);
                    $prop['baloon'] = $this->setByLang($prop['baloon'],$Lang);
                    $Props[$code] = $prop;
                }
                
                
            /*foreach($Props as $code=>$prop){
                
                $prop['name'] ? $prop['name'] : $prop['code'];
                
                // --- --- --- --- ---
                $Props[$code] = $prop;
            }*/
                
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
            $Props = $this->OwnProps;
            $ParentProps = self::get($this->Parent)->OwnProps;
            
            /*
            $Parent = self::get($this->Parent);
            
            // 1. Получить свойства родителя
            if(!$Parent->Url) $ParentProps = [];
            else $ParentProps = $Parent->OwnProps;
            
            // 2. Получить собственный свойства
            $Props = $this->OwnProps;
            foreach($Props as $code=>$prop){
                if(substr($code,0,1) === '_') unset($Props[$code]);
                elseif($code !== 'systype') $Props[$code]['self'] = true;
                else $Props[$code]['self'] = null;
            }
            */
            
            // 3. Склеить свойства родителя и собственные
            foreach($Props as $code=>$prop) $ParentProps[$code] = $prop;
            $Props = $ParentProps;
            //dump($Props);
            return $Props;
            
            
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
            
            // 5. Парсить language
            $Language = app\Kernel::get()->Config->getValue('lang','_def');
            /*foreach($Props as $code=>$prop){
                $prop['name']   = kernel\Language::get($prop['name'])->Value;
                $prop['label']  = kernel\Language::get($prop['label'])->Value;
                $prop['baloon'] = kernel\Language::get($prop['baloon'])->Value;
                
                $prop['name'] ? $prop['name'] : $prop['code'];
                
                // --- --- --- --- ---
                $Props[$code] = $prop;
            }*/
            
            return $Props;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * Получение свойств для форм ввода и списков
     */
    /*protected function getMyProps(){
        return $this->getInstanceValue('_Props',function(){
            return array_filter($this->AllProps,function($val){ return !!$val['self']; });
        });
    }*/
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>