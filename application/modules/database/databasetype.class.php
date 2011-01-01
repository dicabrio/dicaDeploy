<?php

class DatabaseType extends DomainText {

	private static $allowedTypes = array('mysql' => 'mysql');

	public function __construct($sValue) {

		if (!isset(self::$allowedTypes[$sValue])) {
			throw new InvalidArgumentException('Given database type is not allowed');
		}

		parent::__construct($sValue, 3, 255);
	}

}