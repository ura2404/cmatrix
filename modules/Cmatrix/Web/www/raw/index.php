<?php
require_once 'controller.php';
require_once 'model.php';
require_once 'view.php';

$controller = Controller::get(View::get(),Model::get());
$html = $controller->Html;
echo $html;
?>