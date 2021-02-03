<?php
namespace Cmatrix\Structure\Model;

interface iDatamodel {
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun array - массив свойств, требующих счётчиков
     */
    public function getSequenceProps();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun string имя счётчика для свойства
     */
    public function getPropSequenceName($prop);
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array массив свойств, требующих быть primary
     */
    public function getPkProps();
    
}
?>