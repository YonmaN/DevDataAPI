<?php

namespace Zend\Devbar;

class Data {
	/**
	 * @var array
	 */
	static $registry = ['__save_close_flag' => false];
	/**
	 * @var integer
	 */
	static $requestId = 0;
	
	/**
	 * @param string $namespace
	 * @param string $value
	 */
	static function set($namespace, $value) {
		static::$registry[$namespace] = $value;
	}
	
	/**
	 * @param string $namespace
	 */
	static function get($namespace) {
		return static::$registry[$namespace];
	}
	
	/**
	 * @param string $namespace
	 */
	static function remove($namespace) {
		unset(static::$registry[$namespace]);
	}
	
	static function saveClose() {
		if (isset(static::$registry['__save_close_flag']) && static::$registry['__save_close_flag']) {
			trigger_error('Devbar data already written', E_USER_WARNING);
			return ;
		}
	
		unset(static::$registry['__save_close_flag']);
	
		$varpath = get_cfg_var('zend.data_dir') . DIRECTORY_SEPARATOR . 'db';
		$zdbPDO = new \PDO("sqlite:{$varpath}/devbar.db", '', '', array());
	
		$stmt = $zdbPDO->prepare('INSERT INTO devbar_custom_data VALUES(NULL, ?, ?, ?)');
	
		$zdbPDO->beginTransaction();
		foreach(static::$registry as $namespace => &$value) {
			$stmt->execute(array(intval(static::$requestId), $namespace, json_encode($value)));
		}
		$zdbPDO->commit();
	
		$zdbRegistry['__save_close_flag'] = true;
	}
	
}
register_shutdown_function('Zend\Devbar\Data::saveClose');


Data::set('namepsace', [['boom' => 'value']]);
