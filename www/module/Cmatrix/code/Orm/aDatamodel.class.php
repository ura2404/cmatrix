<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-08-18
 */

namespace Cmatrix\Orm;

abstract class aDatamodel {
    abstract protected function getMyUrl();
    abstract protected function getMyName();
    abstract protected function getMyParentUrl();
}
?>