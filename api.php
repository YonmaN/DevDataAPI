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
	$zdbRegistry[$namespace] = $value;
}
/**
 * @param string $namespace
 */
function devbar_get($namespace) {
	global $zdbRegistry;
	return $zdbRegistry[$namespace];
}
/**
 * @param string $namespace
 */
function devbar_unset($namespace) {
	global $zdbRegistry;
	unset($zdbRegistry[$namespace]);
}

function devbar_save_close() {
	global $zdbRegistry, $requestId;
	if (isset($zdbRegistry['__save_close_flag']) && $zdbRegistry['__save_close_flag']) {
		trigger_error('Devbar data already written', E_USER_WARNING);
		return ;
	}
	
	unset($zdbRegistry['__save_close_flag']);
	
	$varpath = get_cfg_var('zend.data_dir') . DIRECTORY_SEPARATOR . 'db';
	$zdbPDO = new PDO("sqlite:{$varpath}/devbar.db", '', '', array());

// 	CREATE TABLE devbar_custom_data (id INTEGER PRIMARY KEY AUTOINCREMENT, request_id INTEGER NOT NULL, namespace VARCHAR(255), data_json VARCHAR(2048));
	$stmt = $zdbPDO->prepare('INSERT INTO devbar_custom_data VALUES(NULL, ?, ?, ?)');
	
	$zdbPDO->beginTransaction();
	foreach($zdbRegistry as $namespace => &$value) {
		$stmt->execute(array(intval($requestId), $namespace, json_encode($value)));
	}
	$zdbPDO->commit();
	
	$zdbRegistry['__save_close_flag'] = true;
}

register_shutdown_function('devbar_save_close');