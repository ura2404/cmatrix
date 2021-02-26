<?php
namespace Cmatrix\Structure\Model;

interface iDatamodel {
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun string - трансформированное имя
     * 
     * например можно скоращать имя или хешировать
     */
    public function getTransName($name);
    
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun array - массив свойств, требующих счётчиков
     */
    public function getSequenceProps();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @retrun string - имя счётчика для свойства
     */
    public function getPropSequenceName($prop);
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имена свойств для primary через запятую
     */
    public function getPkProps();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя primary key
     */
    public function getPkName();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя таблицы
     */
    public function getTableName();
    
    // --- --- --- --- --- --- --- ---
    /**
     *@return string - имя таблицы родителя
     */
    public function getParentTableName();
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя свойства
     */
    public function getPropName($prop);
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имена свойств для index через запятую
     */
    public function getIndexProps(array $props);
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - имя индекса для массива полей
     */
    public function getIndexName(array $props);
    
}
?>