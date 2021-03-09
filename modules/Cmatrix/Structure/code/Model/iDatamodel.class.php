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
     * @return string - имена свойств для primary через запятую
     */
    public function getPkProps();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имена свойств для index через запятую
     */
    public function getIndexProps(array $props);

}
?>