<?php
/**
 * Class Twig
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */
namespace Cmatrix\Web\Mvc\Controller;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;
use \Cmatrix\Vendor as vendor;

class Twig extends \Cmatrix\Web\Mvc\Controller {
    private $Instance;
    
    // --- --- --- --- --- --- --- ---
    function __construct($view, $model){
        parent::__construct($view, $model);
        
        vendor\Kernel::reg('Twig');
        
        $Loader = new \Twig_Loader_Filesystem(web\Ide\Cache::get('forms')->Path);
        
        // --- --- --- --- --- ---
        $Twig = new \Twig_Environment($Loader, [
            'cache' => kernel\Ide\Cache::get('twig')->Path,
            'debug' => true,
            'auto_reload' => true
        ]);
        
        // --- --- --- --- --- ---
        $Twig->addExtension(new \Twig_Extension_Debug());
        //$twig->addExtension(new \Twig_Extensions_Extension_Text());    //TODO:что это?
        
        // --- --- --- --- --- ---
        // добавить проверку "isArray"
        // {% if value is array %}
        // {% endif %}
        $isArray= new \Twig_SimpleTest('array', function ($value){
            return is_array($value);
        });
        $Twig->addTest($isArray);
        
        // проверка содержит подстроку
        // {% if value is contains('строка')%}
        // {% endif %}
        $isContains= new \Twig_SimpleTest('contains', function ($value,$value2){
            return strpos($value,$value2)!==false;
        });
        $Twig->addTest($isContains);
        
        // --- --- --- --- ---
        // --- filters ----
        // {{ string|ltrim }}
        $Twig->addFilter(new \Twig_SimpleFilter('ltrim', 'ltrim'));
        
        // {{ string|rtrim }}
        $Twig->addFilter(new \Twig_SimpleFilter('rtrim', 'rtrim'));
        
        // {{ array|unset(value) }}
        $Twig->addFilter(new \Twig_SimpleFilter('unset', function($value,$value2){
            if(is_array($value) && isset($value[$value2])) unset($value[$value2]);
            return $value;
        }));
        
        // {{ array|unsets(array[value,value]) }}
        $Twig->addFilter(new \Twig_SimpleFilter('unsets', function($value,$value2){
            if(!is_array($value)|| !is_array($value2)) return;
            foreach($value2 as $v) unset($value[$v]);
            return $value;
        }));
        
        // удалить лишние пробелы внутри строки, по краям
        // {{ string|purge }}
        $Twig->addFilter(new \Twig_SimpleFilter('purge', function($value){
            if(is_array($value)) return $value;
            else return str_replace(', ',',',trim(preg_replace("/\s{2,}+/"," ",$value)));
        }));
        
        // --- options ---
        // props of datagrid
        /*
        $Twig->addFilter(new \Twig_SimpleFilter('jeuPropOptions', function($value){
            return \cmWeb\Ui\Jeu\Options::get('prop',$value)->Options;
        }));
        */
        
        // datagrid
        // добавочки для direct
        /*
        $Twig->addFilter(new \Twig_SimpleFilter('jeuDatagridOptions', function($value){
            return \cmWeb\Ui\Jeu\Options::get('datagrid',$value)->Options;
        }));
        */
        // combobox
        /*
        $Twig->addFilter(new \Twig_SimpleFilter('jeuComboboxOptions', function($value){
            return \cmWeb\Ui\Jeu\Options::get('combobox',$value)->Options;
        }));
        */
        
        // linkbutton
        /*
        $Twig->addFilter(new \Twig_SimpleFilter('jeuLinkbuttonOptions', function($value,$params=null){
            return \cmWeb\Ui\Jeu\Options::get('linkbutton',$value,$params)->Options;
        }));
        /*
        
        // searchbox
        /*
        $Twig->addFilter(new \Twig_SimpleFilter('jeuSearchboxOptions', function($value,$params=null){
            return \cmWeb\Ui\Jeu\Options::get('searchbox',$value,$params)->Options;
        }));
        */
        
        $this->Instance = $Twig;
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        $Twig = $this->Instance;
        
        try{
            $Data = $Twig->render($this->View->CacheKey,$this->Model->Data);
            return $Data;
        }
        catch(\Exception $e){
            //throw new cm\Exception\Fatal('Twig templater error<br/>Message: "'.$e->getMessage().'"');
            throw new ex\Error($e->getMessage());
        }
        catch(\Twig_Error $e){
            throw new ex\Error('Twig templater error // '.$e->getRawMessage());
        }
        catch(\Twig_Error_Loader $e){
            throw new ex\Error('Twig templater error // '.$e->getRawMessage());
        }
        catch(\Twig_Error_Runtime $e){
            throw new ex\Error('Twig templater error // '.$e->getRawMessage());
        }
        catch(\Twig_Error_Syntax $e){
            throw new ex\Error('Twig templater error // '.$e->getRawMessage());
        }
    }
}
?>
