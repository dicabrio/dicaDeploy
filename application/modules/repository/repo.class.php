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
		parent::addColumn('type', DataTypes::VARCHAR, 255, true);
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {

		return $this->getAttr('name');
	}

	/**
	 *
	 * @return string
	 */
	public function getLocation() {

		return $this->getAttr('location');
	}

	/**
	 *
	 * @return string
	 */
	public function getLastupdate() {

		return $this->getAttr('lastupdate');
	}

	/**
	 *
	 * @return string
	 */
	public function getRevision() {

		return $this->getAttr('revision');
	}

	/**
	 *
	 * @return string
	 */
	public function getUsername() {

		return $this->getAttr('username');
	}

	/**
	 *
	 * @return string
	 */
	public function getPassword() {

		return $this->getAttr('password');
	}

	/**
	 *
	 * @return string
	 */
	public function getType() {

		return $this->getAttr('type');
		
	}

	/**
	 *
	 * @param string $name
	 */
	public function setName($name) {

		$this->setAttr('name', $name);
	}

	/**
	 *
	 * @param string $location
	 */
	public function setLocation($location) {

		$oldValue = $this->getAttr('location');
		if ($oldValue != $location) {
			$this->unvalidate();
		}

		$this->setAttr('location', $location);
	}

	/**
	 *
	 * @param string $username
	 */
	public function setUsername($username) {

		$oldValue = $this->getAttr('username');
		if ($oldValue != $username) {
			$this->unvalidate();
		}

		$this->setAttr('username', $username);
	}

	/**
	 *
	 * @param string $password
	 */
	public function setPassword($password) {

		$oldValue = $this->getAttr('password');
		if ($oldValue != $password) {
			$this->unvalidate();
		}

		$this->setAttr('password', $password);
	}

	public function setType(RepoType $type) {

		$oldValue = $this->getAttr('type');
		if ($oldValue != $type->getValue()) {
			$this->unvalidate();
		}

		$this->setAttr('type', $type);

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

//	 curl_init('http://github.com/api/v2/json/repos/show/dicabrio/dicadeploy');

//		$res = '{"error":"dicabrio/dicadeployd Repository not found"}';
//		$res = '{
//			"repository": {
//				"url":"https:\/\/github.com\/dicabrio\/dicaDeploy",
//				"watchers":1,
//				"homepage":"http:\/\/www.dicabrio.com",
//				"has_wiki":true,
//				"created_at":"2010/11/24 02:42:12 -0800",
//				"fork":false,
//				"open_issues":4,
//				"private":false,
//				"name":"dicaDeploy",
//				"owner":"dicabrio",
//				"has_issues":true,
//				"pushed_at":"2011/01/01 09:10:10 -0800",
//				"forks":1,
//				"description":"deployment tool",
//				"has_downloads":true
//			}
//		}';
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