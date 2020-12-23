<?php
/**
 * Class Cmatrix\Orm\Entity
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-23
 */

namespace Cmatrix\Orm;

interface iDatamodel{
    /**
     * Функции дополнения свойств сущности до/после создания/получения
     * Можно применять для установки свойств по умолчанию или для фиксации каких-либо значений, например температуры и давления воздуха :) (шутка)
     */
    public function beforeCreate($ob);
    
}
?>