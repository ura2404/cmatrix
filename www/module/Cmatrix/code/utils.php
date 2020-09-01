<?php

/**
 * Набор PHP-функций
 * 
 * array2string      - конвертировать массив в строковое представление
 * array2line        - вытянуть массив с струну
 * arrayIntersect    - пересеч массивы
 * arrayKeing        - создания массива ключ:значение - замена ключа значением
 * arrayDReflection  - отразить массив по диагонали
 * arrayDefs         - дополнить массив
 * arrayMergeReplace - рекурсивно слить массивы заменяя, а не дублируя элементы
 * 
 * arrayFilter      - фильтровать массив по ключу и значению 
 * 
 * isArrayPlain - проверить массив на плоскость (нехешированный одноуровневый массив)
 * 
 * strFupper  - заглавная буква строчная (большая)
 * strFlover  - заглавная буква пропистная (маленькая)
 * strStart   - проверка начинается ли строка на указанную подстроку
 * strEnd     - проверка заканчивается ли строка на указанную подстроку
 * strBetween - выделение подстроки из строки между указанными подстроками
 * strAfter   - выделение подстроки из строки после первой указанной подстроки
 * strRAfter  - выделение подстроки из строки после последней указанной подстроки
 * strBefore  - выделение подстроки из строки до первой указанной подстроки
 * strRBefore - выделение подстроки из строки до последней указанной подстроки
 * cmStrToLower - привести строку к нижнему регистру
 * cmStrToUpper - привести строку к верхнему регистру
 *
 * @author ura ura@itx.ru
 * @version 1.0 2013-01-01
 */

// --- --- --- --- --- --- --- --- --- ---
// --- Общие - --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- ---


// --- --- --- --- --- --- --- --- --- ---
// --- Массивы --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция array2string
 * 
 * Конвертировать массив в строковое представление
 * 
 * @params array $data входящий массив
 * @return string выходящая строка
 *     [100,a:200,b:300,400,500,c:600]
 * 
 * @author ura@urx.su
 * @version 1.0 2017-02-17
 */
function array2string($data){
    if(!is_array($data)) return $data;
    
    $arr = [];
    foreach($params as $key=>$value){
        if(is_array($value)) $value = array2string($value);
            
        // --- если ключ - число, то не выводим его
        if(gettype($key) === 'integer' || ctype_digit($key)) $arr[] = $value;
        else $arr[] = $key .':'. $value;
    }
    return '['. implode(',',$arr) .']';
}

// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция array2line
 * 
 * Вытянуть массив с струну
 * 
 * @params array $data - входящий массив
 * [ 
 *   100,
 *   [
 *     a=>200
 *   ],
 *   [
 *     c=>300
 *   ],
 *   400,
 *   500,
 *   [
 *     d=>600,
 *     [
 *       dd=>700
 *     ]
 *   ]
 * ]
 * @return array
 *     [100,200,300,400,500,600,700]
 * 
 * @author ura@urx.su
 * @version 1.0 2017-02-17
 */
function array2line($data){
    if(!is_array($data)) return $data;
    //dump($data);
    
	$arr = [];
	foreach($data as $val){
		if(is_array($val)){
			$l = array2line($val);
			foreach($l as $ll) $arr[] = $ll;
		}
		else $arr[] = $val;
	}
	
	//dump($arr);
	return $arr;
};

// --- --- --- --- --- --- --- --- --- ---
/**
* Функция intersectArray
*
* Пересеч массивы
* Накатывает второй массив на первый с конца до пересечения
*
* @param array $arr1 - первый массив
* @param array $arr2 - второй массив
* @return array - массив перечечения
*
* @author ura ura@itx.ru
* @version 1.0 2013-06-15
*/
function arrayIntersect($arr1,$arr2){
    $arr_ret = NULL;
    $arr_tmp = $arr1;
    krsort($arr_tmp);
    foreach($arr_tmp as $key=>$value){
        if($arr2[0]==$value){
		    $a1 = array_slice($arr1,$key);
		    $a2 = array_slice($arr2,0,count($a1));
		    if($a1===$a2) $arr_ret = $a1;
        }
    }
    return $arr_ret;
}


// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция arrayKeing
 * 
 * Cоздания массива ключ:значение - замена ключа значением
 * 
 * @param array $arr входящий массив
 *     [
 *         0 => [
 *             'code' => 'aaa',
 *             'name' => 'name_aaa',
 *         ],
 *         1 => [
 *             'code' => 'bbb',
 *             'name' => 'name_b',
 *         ]
 *     ]
 * 
 * return arrayKeing($arr,'code');
 * 
 * @param array исходящий массив
 *     [
 *         'aaa' => [
 *             'code' => 'aaa',
 *             'name' => 'name_aaa',
 *         ],
 *         'bbb' => [
 *             'code' => 'bbb',
 *             'name' => 'name_b',
 *         ]
 *     ]
 * 
 */
function arrayKeing(array $arr,$code){
    return array_combine(array_column($arr,$code), $arr);
    
    
    $arr_res = [];
    /*
    foreach($arr as $key=>$value){
        if(!is_array($value) || !isset($value[$code])) continue;
        $arr[$value[$code]] = $value; 
        unset($arr[$key]);
    }
    return $arr;
    */
    
    foreach($arr as $key=>$value){
        if(!is_array($value) || !isset($value[$code])) $arr_res[$key] = $value;
        else $arr_res[$value[$code]] = $value; 
    }
    return $arr_res;
}

// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция arrayDReflection
 * 
 * Отразить массив по диагонали
 * 
 * @param array $arr - входящий массив
 * [
 *     [a1,a2,a3],
 *     [b1,b2,b3],
 *     [c1,c2,c3],
 * ]
 * 
 * @return array $arr - выходящий массив
 * [
 *     [a1,b1,c1],
 *     [a2,b2,c2],
 *     [a3,b3,c3],
 * ]
 */
function arrayDReflection(array $arr){
    $_maxX = function() use($arr){
        $arr_tmp = [];
        foreach($arr as $y) $arr_tmp[] = count($y);
        return count($arr_tmp) ? max($arr_tmp) : 0;
    };
    
    $_maxY = function() use($arr){
        return count($arr);
    };
    
    $_convert = function($maxX,$maxY) use($arr){
        $arr_tmp = [];
        for($x=0;$x<$maxY;$x++){
            $y=0;
            foreach($arr[$x] as $k=>$v){
                $arr_tmp[$y][$k] = $v;
                $y++;
            }
        }
        return $arr_tmp;
    };
    
    $maxX = $_maxX();
    $maxY = $_maxY();
    $arr = $_convert($maxX,$maxY);
    return $arr;
}

// --- --- --- --- --- ---
/**
 * Функция arrayDefs
 * 
 * Дополнить массив
 * К первому массиву добавляются по очереди !!недостающие!! элементы из последующих
 * !!! НЕ рекурсивно
 */
/*function array_defs(...$arrays){
    return arrayDefs($arrays);
}*/
function arrayDefs(...$arrays){
    if(!count($arrays)) return [];
    
    $first = array_shift($arrays);
    if(!count($arrays)) return $first;
    
    foreach($arrays as $arr){
        $arr = array_diff_key($arr,$first);     // находим элементы последующего массива, отсутствующие в первом массиве
        $first = array_merge($first,$arr);      // добавим в первый массив недостающие элелементы из последующего
    }
    return $first;
}

/**
 * Рекурсивно слить массивы заменяя, а не дублируя элементы
 */
function arrayMergeReplace(...$arrays){
    if(!count($arrays)) return [];
    
    $first = array_shift($arrays);
    if(!count($arrays)) return $first;

    $_rec = function($first,$second) use(&$_rec){
        foreach($second as $key=>$value){
            if(!array_key_exists($key,$first)) $first[$key] = $value;
            else{
                // если массивы, то слить
                if(is_array($first[$key]) && is_array($value)) $first[$key] = $_rec($first[$key],$value);
                //elseif(is_array($first[$key]) && !is_array($value)) $first[$key][] = $value;
                //elseif(!is_array($first[$key]) && is_array($value)){
                //    $first[$key] = $value;
                //    $first[$key][] = $first[$key];
                //}
                // если кто-то не массив, то изпользовать второй
                elseif(!is_array($first[$key]) && is_array($value))  $first[$key] = $value;
                elseif(is_array($first[$key]) && !is_array($value))  $first[$key] = $value;
                elseif(!is_array($first[$key]) && !is_array($value)) $first[$key] = $value;
            }
        }
        return $first;
    };

    foreach($arrays as $arr){
        $first = $_rec($first,$arr);
    }    
    return $first;
}

/**
 * Получить значение по ключу
 * 
 * arrayGetValue(['a','b','c'],[
 *     'a' => [
 *         'b'=> [
 *             'c' => 'value1',
 *             'd' => 'value2'
 *         ]
 *     ]
 * ])
 * 
 */
function arrayGetValue(array $key,array $data,$default=null){
    // 1. получить первый индекс цепочки
    $K = array_shift($key);
    
    // 2. если значение есть и индексов больше нет, то вывести значение
    // если индексы ещё есть и значение массив, то рекурсия
    // если индексы ещё есть и значение НЕ массив, то false
    //
    // если значения нет, то вывести default
    if(array_key_exists($K,$data)){
        if(!count($key)) return $data[$K];
        else{
            if(is_array($data[$K])) return arrayGetValue($key,$data[$K]);
            else return false;
        }
    }
    else return $default;
}

/*
    public function getValue($path=null,$default=null){
        if($path === null) return $this->Data;

        $_fun = function($arr,$ini) use(&$_fun,$default){
            if(count($arr)>1){
                $ind = $arr[0];
                array_shift($arr);
                return isset($ini[$ind]) ? $_fun($arr,$ini[$ind]) : $default;
            }
            else return isset($ini[$arr[0]]) ? $ini[$arr[0]] : ($default || is_array($default) ? $default : false);
        };

        return $_fun(explode("/",$path),$this->Data);
    }
*/


// --- --- --- --- --- ---
/**
 * Функция arrayFilter
 * 
 * Фильтровать массив по ключу и значению с помощию callback функции
 */
function array_combo_filter(array $arr, $_callback){
    return arrayFilter($arr,$_callback);
}
function arrayFilter(array $arr, \Closure $_callback){
    foreach($arr as $key=>$value){
        if(!$_callback($key,$value)) unset($arr[$key]);
    }
    return $arr;
}

// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция isArrayPlain
 * 
 * Проверить массив на плоскость (нехешированный одноуровневый массив)
 * 
 * Массив такого вида является плоским нехэшированным
 *     [
 *         0 => aaa
 *         1 => bbb
 *         ...
 *         ...
 *         n-2 = >xxx
 *         n-1 = >yyy
 *         n   = >zzz
 *     ]
 * 
 * @param array $arr проверяемый массив
 * @return bool true - плоский нехэшированный массив, false - иной массив
 */
function isArrayPlain(array $arr){
    $fl = true;
    foreach($arr as $key=>$value){
        if(is_array($value) || !(gettype($key)==='integer' || ctype_digit($key))){
            $fl = false;
            break;
        }
    }
    return $fl;
}






// --- --- --- --- --- --- --- --- --- ---
// --- Строки  --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- ---
/**
 * Функция str_fupper
 * Первая буква теперь заглавная
 */
function str_fupper($str){
    return strFupper($str);
}
function strFupper($str){
    return str_toupper(str_substr($str,0,1)) . str_tolower(str_substr($str,1)); 
}

/**
 * Функция str_lupper
 * Первая буква теперь строчная
 */
function str_flower($str){
    return strFlower($str);
}
function strFlower($str){
    return str_tolower(str_substr($str,0,1)) . str_substr($str,1);
}

/**
 * Функция strStart
 *
 * Проверка начинается ли строка на указанную подстроку
 *
 * @param string $str	строка для анализа
 * @param string|array $mask	искомые подстрока или массив подстрок
 * @return bool		результат проверки
 *
 * @author ura ura@itx.ru
 * @version 1.0
 * @date 2013-06-15
 */
function str_start($str,$mask){
    return strStart($str,$mask);
}
function strStart($str,$mask){
    if(gettype($str)!=='string') return false;
    //dump($str,'UTILS');
    
    $encoding = mb_detect_encoding($str);
	$mask = is_array($mask) ? $mask : array($mask);

	$fl = false;
	foreach($mask as $m){
	    $len = mb_strlen($m,$encoding);
	    $p = mb_substr($str,0,$len,$encoding)===$m ? true : false;
	    $fl = $fl || $p;
	}
	return $fl;
}

/**
 * Функция strEnd
 *
 * Проверка оканчивается  ли строка на указанную подстроку
 *
 * @param string $str	строка для анализа
 * @param string|array $mask	искомые подстрока или массив подстрок
 * @return bool		результат проверки
 *
 * @author ura ura@itx.ru
 * @version 1.0
 * @date 2013-06-15
 */
function str_end($str,$mask){
    return strEnd($str,$mask);
}
function strEnd($str,$mask){
    if(gettype($str)!=='string') return false;

	$encoding = mb_detect_encoding($str);
	$mask = is_array($mask) ? $mask : array($mask);

	$fl = false;
        $len1 = mb_strlen($str,$encoding);
	foreach($mask as $m){
	    $len2 = mb_strlen($m,$encoding);
	    $p = mb_substr($str,$len1-$len2,$len2,$encoding)===$m ? true : false;
	    $fl = $fl || $p;
	}
	return $fl;
}


/**
* Функция strBetween
*
* Выделение подстроки из строки между указанными подстроками
*
* @param string $str	строка для анализа
* @param string $char1	искомая подстрока1
* @param string $char2	искомая подстрока2
* @param bool $fl		если true, то выделять с подстроками поиска, false - только между ними
* @return string		результирующая строка
*
* @author ura ura@itx.ru
* @version 1.0
*/
function str_between($str,$char1,$char2,$fl=false){
    return strBetween($str,$char1,$char2,$fl);
}
function strBetween($str,$char1,$char2,$fl=false){
    $encoding = mb_detect_encoding($str);
    $pos1 = mb_strpos($str,$char1,0,$encoding);
    $pos2 = mb_strpos($str,$char2,0,$encoding);
    if($pos1 !==false && $pos2!=false){
	    if($fl){
	//$len1 = mb_strlen($char1,$encoding);
	$len2 = mb_strlen($char2,$encoding);
		return mb_substr($str,$pos1,$pos2-$pos1+$len2,$encoding);
	    }else{
	$len1 = mb_strlen($char1,$encoding);
	//$len2 = mb_strlen($char2,$encoding);
		return mb_substr($str,$pos1+$len1,$pos2-$pos1-$len1,$encoding);
	    }
    }else return $str;
}

/**
 * Функция strAfter
 *
 * Выделение подстроки из строки после первой указанной подстроки
 *
 * @param string $str	строка для анализа
 * @oaram string $char	искомая подстрока
 * @param bool $fl		если true, то выделять с подстрокой поиска, false - только после неё
 * @return string|null	результирующая строка или null, если не найден разделитель
 * 
 *
 * @author ura ura@itx.ru
 * @version 1.0
 */
function str_after($str,$char,$fl=false){
    return strAfter($str,$char,$fl);
}
function strAfter($str,$char,$fl=false){
    $encoding = mb_detect_encoding($str);
	$slen = mb_strlen($str,$encoding);

	if(($pos=mb_strpos($str,$char,0,$encoding))!==false){
        if($fl){
            return mb_substr($str,$pos,$slen-$pos,$encoding);
        }else{
		    $len = mb_strlen($char,$encoding);
            return mb_substr($str,$pos+$len,$slen-$pos-$len,$encoding);
        }
    }
	else return null;
}

/**
 * Функция strRAfter
 *
 * Выделение подстроки из строки после последней указанной подстроки
 *
 * @param string $str	строка для анализа
 * @oaram string $char	искомая подстрока
 * @param bool $fl		если true, то выделять с подстрокой поиска, false - только после неё
 * @return string|null	результирующая строка или null, если не найден разделитель
 *
 * @author ura ura@itx.ru
 * @version 1.0 2014-05-06
 */
function str_rafter($str,$char,$fl=false){
    return strRAfter($str,$char,$fl);
}
function strRAfter($str,$char,$fl=false){
	//if(gettype($str)!=='string') return $str;

	$encoding = mb_detect_encoding($str);
	$slen = mb_strlen($str,$encoding);
	
    if(($pos=mb_strrpos($str,$char,0,$encoding))!==FALSE){
        if($fl){
            return mb_substr($str,$pos,$slen-$pos,$encoding);
        }else{
            $len = mb_strlen($char,$encoding);
            return mb_substr($str,$pos+$len,$slen-$pos-$len,$encoding);
        }
    }
    else return null;
}

/**
 * Функция strBefore
 *
 * Выделение подстроки из строки до первой указанной подстроки
 *
 * @param string $str	строка для анализа
 * @param string $char	искомая подстрока
 * @param bool $fl		если true, то выделять с подстрокой поиска, false - только до неё
 * @return string       результирующая строка или исходная строка, если не найден разделитель
 *
 * @author ura ura@itx.ru
 * @version 1.0
 */
function str_before($str,$char,$fl=false){
    return strBefore($str,$char,$fl);
}
function strBefore($str,$char,$fl=false){
	$encoding = mb_detect_encoding($str);
    if(($pos=mb_strpos($str,$char,0,$encoding))!==false){
        if($fl){
            $len = mb_strlen($char,$encoding);
            return mb_substr($str,0,$pos+$len,$encoding);
        }else return mb_substr($str,0,$pos,$encoding);
	}
    else return $str;
}

/**
 * Функция strRBefore
 *
 * Выделение подстроки из строки до последней указанной подстроки
 *
 * @param string $str	строка для анализа
 * @param string $char	искомая подстрока
 * @param bool $fl		если true, то выделять с подстрокой поиска, false - только до неё
 * @return string|null	результирующая строка или null, если искомая подстрока не входит в строку
 *
 * @author ura ura@itx.ru
 * @version 1.0
 */
function str_rbefore($str,$char,$fl=false){
    return strRBefore($str,$char,$fl);
}
function strRBefore($str,$char,$fl=false){
	$encoding = mb_detect_encoding($str);
    if(($pos=mb_strrpos($str,$char,0,$encoding))!==false){
        if($fl){
            $len = mb_strlen($char,$encoding);
            return mb_substr($str,0,$pos+$len,$encoding);
        }else return mb_substr($str,0,$pos,$encoding);
	}
    else return $str;
}

/**
 * Функция cmStrToLower
 *
 * Привести строку к нижнему регистру
 * 
 * @author ura ura@itx.ru
 * @version 1.0
 */
function cmStrToLower($str){
	$encoding = mb_detect_encoding($str);
    return mb_strtolower($str,$encoding);
}
 
/**
 * Функция cmStrToUpper
 *
 * Привести строку к верхнему регистру
 * 
 * @author ura ura@itx.ru
 * @version 1.0
 */
function cmStrToUpper($str){
	$encoding = mb_detect_encoding($str);
    return mb_strtoupper($str,$encoding);
}













    //define('DO_FETCH_ASSOC',2);

  //------------------------------------------------------------
  function dump($str,$name=NULL){
    //echo gettype($str).'<br/>'."\n";

    if(is_resource($str)){
      echo ($name!=NULL?$name:'').'-----RESOURCE <b>'.get_resource_type($str).'</b>------------vvv------------'.'<br/>'."\n";
      switch(get_resource_type($str)){
        case 'pgsql result' : echo '-----result status='.pg_result_status($str);
                              switch(pg_result_status($str)){
                                case PGSQL_EMPTY_QUERY    : echo ' (PGSQL_EMPTY_QUERY)'; break;
                                case PGSQL_COMMAND_OK     : echo ' (PGSQL_COMMAND_OK)'; break;
                                case PGSQL_TUPLES_OK      : echo ' (PGSQL_TUPLES_OK)'; break;
                                case PGSQL_COPY_TO        : echo ' (PGSQL_COPY_TO)'; break;
                                case PGSQL_COPY_FROM      : echo ' (PGSQL_COPY_FROM)'; break;
                                case PGSQL_BAD_RESPONSE   : echo ' (PGSQL_BAD_RESPONSE)'; break;
                                case PGSQL_NONFATAL_ERROR : echo ' (PGSQL_NONFATAL_ERROR)'; break;
                                case GSQL_FATAL_ERROR     : echo ' (GSQL_FATAL_ERROR)'; break;
                              }
                              echo '<br/>'."\n";
        default : break;
      }
    }elseif(is_object($str)) echo ($name!=NULL?$name:'').'-----OBJECT '.'------------vvv------------'.'<br/>'."\n";
    elseif(is_array($str)) echo ($name!=NULL?$name:'').'-----ARRAY '.'------------vvv------------'.'<br/>'."\n";
    else if($name!=NULL) echo $name."=";

    if($str===FALSE) echo ' ===FALSE'.'<br/>'."\n";
    elseif($str===NULL) echo ' ===NULL'.'<br/>'."\n";
    elseif($str===TRUE) echo ' ===TRUE'.'<br/>'."\n";
    else{
      if(is_resource($str)){
        switch(get_resource_type($str)){
          case 'pgsql result' : //echo get_resource_type($str).'<br/>'."\n";
                                //$n = pg_num_rows($str);
                                $r = array();
                                for($i=0;$i<pg_num_rows($str);$i++) $r[] = pg_fetch_array($str);
                                echo html_entity_decode('<pre>'.print_r($r,1).'</pre>')."\n";
                                break;
          default : echo get_resource_type($str).'<br/>'."\n";

        }
      }elseif(is_object($str)) echo html_entity_decode('<pre>'.print_r($str,1).'</pre>')."\n";
      elseif(is_array($str)) echo html_entity_decode('<pre>'.print_r($str,1).'</pre>')."\n";
     else echo html_entity_decode(print_r($str,1)).'<br/>'."\n";
    }

    if(is_resource($str)) echo ($name!=NULL?$name:'').'-----RESOURCE <b>'.get_resource_type($str).'</b>------------^^^------------'.'<br/>'."\n";
    elseif(is_object($str)) echo ($name!=NULL?$name:'').'-----OBJECT '.'------------^^^------------'.'<br/>'."\n";
    elseif(is_array($str)) echo ($name!=NULL?$name:'').'-----ARRAY '.'------------^^^------------'.'<br/>'."\n";
  }


  //------------------------------------------------------------
  function xhtml($str,$_config=NULL){
//    return $str;
//    file_put_contents('/var/tmp/4545-1',$str);

//    $str = str_replace("'","`",$str);

    $config = array(
      'show-body-only' =>false,
//      'input-encoding' =>  'utf8',
//      'char-encoding' => 'utf8',
//      'output-html' => false,
//      'output-xml' => true,
      'output-xhtml' => true,
      'wrap' => 0,
      'clean' => false,
      'add-xml-decl' => false,
      'add-xml-space' => false,
      'numeric-entities' => false,
      'ascii-chars' => false,
//      'doctype' => 'strict',
      'bare' => false,
      'fix-uri' => true,
      'indent' => true,
      'indent-spaces' => 2,
      'tab-size' => 8,
      'wrap-attributes' => true,
      'indent-attributes' => false,
      'join-classes' => false,
      'join-styles' => false,
      'enclose-block-text' => false,
      'fix-bad-comments' => false,
      'fix-backslash' => true,
      'replace-color' => false,
      'wrap-asp' => false,
      'wrap-jste' => false,
      'wrap-php' => false,
      'write-back' => false,
      'drop-proprietary-attributes' => false,
      'hide-comments' => false,
      'hide-endtags' => false,
      'literal-attributes' => false,
      
      'drop-empty-paras' => true,
      
      'enclose-text' => false,
      'quote-ampersand' => true,
      'quote-marks' => false,
      'quote-nbsp' => false,
      'vertical-space' => false,
      'wrap-script-literals' => false,
      'tidy-mark' => false,
      'merge-divs' => false,
      'merge-spans' => false,
      'repeated-attributes' => 'keep-last',
      'break-before-br' => false
    );

    $tidy = new tidy();
    $config = is_array($_config)?array_merge($config,$_config):$config;

//    $tidy->parseString(stripslashes($str),$config,'utf8');
    $tidy->parseString($str,$config,'utf8');

//    $tidy->cleanRepair();
    $ret = tidy_get_output($tidy);
//    file_put_contents('/var/tmp/4545-2',$ret);

    if($config['show-body-only']==true && $config['output-xml']=='xml'){
      $n1 = strpos($ret,'<body>')+6;
      $n2 = strrpos($ret,'</body>');
      return trim(substr($ret,$n1,$n2-$n1));
    }else return $ret;
  }
/** ???? */













// --- --- --- --- --- ---
/**
* Функция hid
*
* Предназначена для генерации 32-разрядной уникальной строки
* или хеширвание существующей строки
*
* @param string $str|null	строка, которая хешируется
* @return string md5 хэш
*
* @author ura ura@itx.ru
* @version 1.0 2013-06-15
*/
function hid($str=NULL){
    if($str===NULL) $str = rand().time().microtime();
    return md5($str);
}


/**
* Функция isHID($str)
*
* Проверка является ли строка md5 хешем
*
* @param string $str	анализируемая строка
* @return bool		результат проверки
*
* @author ura ura@itx.ru
* @version 1.1 2013-06-15
*   изменён алгоритм проверки, теперь используется регулярка
*
* @version v1.0 2012-01-01 
*   ввод в эксплуатацию
*/
function isHID($str){
    //return strlen($str)==32 ? true : false;
    return !empty($str) && preg_match('/^[a-f0-9]{32}$/', $str);
}




    //------------------------------------------------------------
    // подстрока от первого символа до $char
    // если $fl=true - включительно
    // если $fl=false - не включительно
    /*
    function str_substr($str,$char,$fl=false){
      if(strpos($str,$char)){
        if($fl) return substr($str,0,strpos($char)+1);
        else return substr($str,0,strpos($char));
      }else return $str;    
    }
    */


    /**
    * Функция str_contains
    *
    * Проверка вхождения подстрок в строку
    *
    * @param string $str	строка для анализа
    * @param string|array $param	искомые строка или массив подстрок
    * @param bool $fl	если true, то должны входить все подстроки, false - хотябы одна
    * @return bool		результат проверки
    *
    * @author ura ura@itx.ru
    * @version 1.0
    */
    function str_contains($str,$param,$fl=false){
	$param = is_array($param) ? $param : array($param);
	$encoding = mb_detect_encoding($str);

	$result = $fl ? true : false;
	foreach($param as $char){
	    $pr = mb_strpos($str,$char,0,$encoding)!==FALSE ? true : false;
	    if($fl) $result = $result && $pr;
    	    else $result = $result || $pr;
	}
	return $result;
    }






    /**
    * Функция str_tolower
    *
    * Перевод строк в нижний регистр
    *
    * @param string $str	строка для анализа
    * @param integer $n		позиция, до которой трансформировать строку
    * @return string		результирующая строка
    *
    * @author ura ura@itx.ru
    * @version 1.0
    * @data 2014-02-07
    */
    function str_tolower($str,$n=null){
	$encoding = mb_detect_encoding($str);
	if($n){
	    $s1 = mb_substr($str,0,$n);
	    $s2 = mb_substr($str,$n);
	    return mb_strtolower($s1,$encoding) . $s2;
	}
        else return mb_strtolower($str,$encoding);
    }


    /**
    * Функция str_toupper
    *
    * Перевод строк в верхний регистр
    *
    * @param string $str	строка для анализа
    * @param integer $n		позиция, до которой трансформировать строку
    * @return string		результирующая строка
    *
    * @author ura ura@itx.ru
    * @version 1.0
    * @data 2014-02-07
    */
    function str_toupper($str,$n=null){
	$encoding = mb_detect_encoding($str);
	if($n){
	    $s1 = mb_substr($str,0,$n);
	    $s2 = mb_substr($str,$n);
	    return mb_strtoupper($s1,$encoding) . $s2;
	}
        else return mb_strtoupper($str,$encoding);
    }


	/**
	* Функция str_count
	*
	* Подсчёт количества подстрок
	*
	* @param string $str	строка для анализа
	* @param string $char	подстрока для поиска
	* @return integer	количество вхождений
	*
	* @author ura ura@itx.ru
	* @version 1.0
	* @data 2014-02-07
	*/
	function str_count($str,$char){
		$encoding = mb_detect_encoding($str);
		return mb_substr_count($str,$char,$encoding);
	}


	/**
	* Функция str_len
	*
	* Длина строки
	*
	* @param string $str	строка для анализа
	*
	* @author ura ura@itx.ru
	* @version 1.0
	* @data 2015-02-09
	*/
	function str_len($str){
		$encoding = mb_detect_encoding($str);
		return mb_strlen($str,$encoding);
	}


	/* ------------------------------- */
	/**
	* Функция str_substr
	* Выделение подстроки
	*/
	function str_substr($str,$begin,$count=null){
		$encoding = mb_detect_encoding($str);
		return mb_substr($str,$begin,$count,$encoding);
	}


	/* ------------------------------- */
	/**
	* Функция str_strpos
	* Поиск подстроки
	*/
	function str_strpos($str,$char){
		$encoding = mb_detect_encoding($str);
		return mb_strpos($str,$char);
	}



    /**
    * Функция timeDiff
    *
    * Расчёт разницы времени
    *
    * @param time $time1	время1
    * @param time $time2	время2
    * @return array		массив разницы в днях, часах, минутах, секундах
    *				например: ['day'=>2,'hour'=>13,'min'=>25,'sec'=>12];
    *
    * @author ura ura@itx.ru
    * @version 1.0
    * @data 2013-01-01
    */
    function timeDiff($time1,$time2){
	$sec = abs($time2-$time1);
	$min  = $sec/60;
	$hour = $min/60;
	$day  = $hour/24;
    
	$arr['day']  = floor($day);
	$arr['hour'] = floor($hour - $arr['day']*24);
	$arr['min']  = floor($min  - $arr['day']*24*60    - $arr['hour']*60);
	$arr['sec']  = floor($sec  - $arr['day']*24*60*60 - $arr['hour']*60*60 - $arr['min']*60);
  
	return $arr;
     }

/**
* Функция isSerialized($str)
*
* Проверка является ли строка сериализованным массивом
*
* @param string $str анализируемая строка
* @return bool результат проверки
*
* @author ura ura@itx.ru
* @version v1.0 2015-09-20 
*/
function isSerialized($str){
    if(gettype($str) !== 'string') return false;
    return (strpos($str,"{")!==false && strpos($str,"}")!==false && strpos($str,":")!==false  && strpos($str,";")!==false);
}

// --- --- --- ---
/**
 * Сжатие контента - удаление пробелов, переносов строк, задвоенных символов
 */
function jamContent($content){
	$content .= "\n"; // для упрощения алгоритма, чтоб не проверять конец файла на наличие этого символа
	
	// удаление коментов '/* */'
	$rec1 = function($res) use(&$rec1){
		if(($pos = strpos($res,"/*")) === false) return $res;
		else{
			$before = str_before($res,"/*");
			$after = str_after($res,"*/");
			return $rec1($before.$after);
		}
	};
	
	// удаление коментов '//''
	$rec2 = function($res) use(&$rec2){
		if(($pos = strpos($res,"//")) === false) return $res;
		else{
			$before = str_before($res,"//");
			$after = str_after($res,"//");
			$after = str_substr($after,str_strpos($after,"\n"));
			return $rec2($before.$after);
		}
	};

	$arr = [
		"\n" 	=> "",
		"\t" 	=> "",
		
		" : " 	=> ":",
		"  " 	=> "",
		" = " 	=> "=",
		" || " 	=> "||",
		
		" + " 	=> "+",
		"+ " 	=> "+",
		" +" 	=> "+",
		
		" * " 	=> "*",
		"* " 	=> "*",
		" *" 	=> "*",
		
		" === " => "===",
		"=== " => "===",
		" ===" => "===",
		
		" !== " => "!==",
		"!== " => "!==",
		" !==" => "!==",
		
		" }" 	=> "}",
		"{ " 	=> "{",
		
		"+= " 	=> "+=",
		"; " 	=> ";",
		": "    => ":",
	];
	
	return str_replace(array_keys($arr),array_values($arr),$rec2($rec1($content)));
}
?>