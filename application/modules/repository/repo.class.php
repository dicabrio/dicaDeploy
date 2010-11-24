<?php

class Repo extends DataRecord implements DomainEntity {


	/**
	 * constructor
	 *
	 * @param int $id
	 */
	public function __construct($id=null) {
		parent::__construct(__CLASS__, $id);
	}

	/**
	 * (non-PHPdoc)
	 * @see data/DataRecord#defineColumns()
	 */
	protected function defineColumns() {

		parent::addColumn('id', DataTypes::INT, false, true);
		parent::addColumn('name', DataTypes::VARCHAR, 255, true);
		parent::addColumn('location', DataTypes::VARCHAR, 255, true);
		parent::addColumn('lastupdate', DataTypes::VARCHAR, 255, true);
		parent::addColumn('revision', DataTypes::VARCHAR, 255, true);
		parent::addColumn('username', DataTypes::VARCHAR, 255, true);
		parent::addColumn('password', DataTypes::VARCHAR, 255, true);
		parent::addColumn('validated', DataTypes::INT, false, true);

	}

	public function getName() {

		return $this->getAttr('name');
		
	}

	public function getLocation() {

		return $this->getAttr('location');
		
	}

	public function getLastupdate() {

		return $this->getAttr('lastupdate');
		
	}

	public function getRevision() {

		return $this->getAttr('revision');
		
	}

	public function getUsername() {

		return $this->getAttr('username');

	}

	public function getPassword() {

		return $this->getAttr('password');
		
	}

	public function setName($name) {

		$this->setAttr('name', $name);

	}

	public function setLocation($location) {

		$oldValue = $this->getAttr('location');
		if ($oldValue != $location) {
			$this->unvalidate();
		}

		$this->setAttr('location', $location);

	}

	public function setUsername($username) {

		$oldValue = $this->getAttr('username');
		if ($oldValue != $username) {
			$this->unvalidate();
		}

		$this->setAttr('username', $username);

	}

	public function setPassword($password) {

		$oldValue = $this->getAttr('password');
		if ($oldValue != $password) {
			$this->unvalidate();
		}

		$this->setAttr('password', $password);

	}

	/**
	 * @return bool
	 */
	public function isValidated() {

		return ($this->getAttr('validated') == 1);

	}

	/**
	 * we need to validate if this repository is ok. So connect to the server
	 * and check if this repository is alive
	 * 
	 */
	public function validate() {


		
	}

	/**
	 * This function will set the validated flag to false.
	 * This is method that's used internally and is called when certain
	 * values are modified
	 */
	private function unvalidate() {
		
		$this->setAttr('validated', 0);

	}

	/**
	 * @return array
	 */
	public static function findAll() {

		return parent::findAll(__CLASS__, parent::ALL);
		
	}
}