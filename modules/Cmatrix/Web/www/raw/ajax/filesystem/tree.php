<?php
header("Content-type: application/json");

require_once ('../../common.php');

$id = isset($_POST['id']) ? $_POST['id'] : null;

// --- --- --- --- --- --- ---
$fun_root = function(){
    return [
		[
			'id' => '/',
			'text' => 'Файловая система',
			'state' => 'closed',
			'type' => 'root',
			'top' => true,
			'iconCls' => 'fa fa-fw fa-folder-o',
		]
	];
};

// --- --- --- --- --- --- ---
$fun_node = function() use($id){
    
	$id = $id==='/' ? null : $id;
	//$root_path = realpath(dirname(__FILE__).'/../../../..'). '/' .$id;
	$root_path = realpath(RAW_ROOT. '/' .$id);

	$files = scandir($root_path);

	$dirs = [];
	$fils = [];
	foreach($files as $file){
        if($file === '.' || $file === '..') continue;
        $path = $root_path .'/'. $file;
        
        if(is_dir($path)) $dirs[] = [
			'id' => ($id ? $id.'/' : null). $file,
			//'text' => '<i class="fa fa-fw fa-folder-o"></i>' . $file,
			'text' => $file,
			'state' => 'closed',
			'type' => 'folder',
			'iconCls' => 'fa fa-fw fa-folder-o',
			//'iconCls' => 'icon-search'
		];
		else $fils[] = [
			'id' => ($id ? $id.'/' : null). $file,
			//'text' => '<i class="fa fa-fw fa-file-o" style="margin-right:3px;"></i>' . $file,
			'text' => $file,
			'type' => 'file',
			'iconCls' => 'fa fa-fw fa-file-o',
		];	    
	}
	
	foreach($fils as $f) $dirs[] = $f;
	return $dirs;
};

// --- --- --- --- --- --- ---
if($id === null) $tree = $fun_root();
else $tree = $fun_node();

echo json_encode($tree);

?>