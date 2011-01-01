<?php
/**
 * 
 */
class Database extends DataRecord {

	/**
	 * @param int $id
	 */
	public function __construct($id=null) {

		parent::__construct(__CLASS__, $id);
		
	}

	protected function defineColumns() {

		parent::addColumn('id', DataTypes::INT, false, true);
		parent::addColumn('type', DataTypes::VARCHAR, 255, true);
		parent::addColumn('name', DataTypes::VARCHAR, 255);
		parent::addColumn('user', DataTypes::VARCHAR, 255);
		parent::addColumn('host', DataTypes::VARCHAR, 255);
		parent::addColumn('password', DataTypes::VARCHAR, 255);
		parent::addColumn('validated', DataTypes::INT, false, true);
		
	}

	/**
	 * @return string
	 */
	public function getType() {

		return $this->getAttr('type');
		
	}

	/**
	 * @return string
	 */
	public function getName() {

		return $this->getAttr('name');
		
	}

	public function getUser() {

		return $this->getAttr('user');

	}

	public function getPassword() {

		return $this->getAttr('password');

	}

	public function getHost() {

		return $this->getAttr('host');

	}

	/**
	 * @param DatabaseType $type
	 */
	public function setType(DatabaseType $type) {

		$this->setAttr('type', $type);
		
	}

	/**
	 * 
	 */
	public function setName($name) {

		$this->setAttr('name', $name);

	}

	public function setUser(Username $user) {

		$this->setAttr('user', $user);
		
	}

	public function setHost(Host $host) {

		$this->setAttr('host', $host->getValue());

	}

	public function setPassword(Password $password) {

		$this->setAttr('password', $password->getValue());
		
	}

	public function isValidated() {

		return ($this->getAttr('validated') == 1);
		
	}

	/**
	 * @return array
	 */
	public static function findAll() {

		return parent::findAll(__CLASS__, parent::ALL);

	}

}
