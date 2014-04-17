<?php
global $zdbRegistry;
$zdbRegistry = ['__save_close_flag' => false];

global $requestId;
/**
 * @param string $namespace
 * @param string $value
 */
function devbar_set($namespace, $value) {
	global $zdbRegistry;
	
}
/**
 * @param string $namespace
 */
function devbar_get($namespace) {
	global $zdbRegistry;
	
}
/**
 * @param string $namespace
 */
function devbar_unset($namespace) {
	global $zdbRegistry;
	
}

function devbar_save_close() {
	global $zdbRegistry;
	if ($zdbRegistry['__save_close_flag']) {
		trigger_error('Devbar data already written', E_USER_WARNING);
		return ;
	}
	
	unset($zdbRegistry['__save_close_flag']);
	
	/* $varpath = get_cfg_var('zend.data_dir') . DIRECTORY_SEPARATOR . 'db';
	$zdbPDO = new PDO("sqlite:{$varpath}/devbar.db", '', '', array());
	/// id, requestid, namespace, rowData
	$zdbPDO->prepare('INSERT INTO devbar_custom_data VALUES(NULL, ?, ?, ?)');
	$zdbPDO->beginTransaction();
	*/
	foreach(array_filter($zdbRegistry, function(){
		print_r(func_get_args());
	}) as $namespace => &$value) {
		
	}
	echo 'asdfasdfsdfa';
	/*$zdbPDO->commit(); */
	$zdbRegistry['__save_close_flag'] = true;
}

register_shutdown_function('devbar_save_close');