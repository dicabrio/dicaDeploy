<?php

include('Crypt/RSA.php');
include('Net/SSH2.php');

class Server extends DataRecord {

	/**
	 *
	 * @var Repo
	 */
	private $repo;

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
		parent::addColumn('hostname', DataTypes::VARCHAR, 255, true);
		parent::addColumn('user', DataTypes::VARCHAR, 255, true);
		parent::addColumn('password', DataTypes::VARCHAR, 255, true);
		parent::addColumn('repopath', DataTypes::TEXT, false, true);
		parent::addColumn('repo_id', DataTypes::INT, false, true);
		parent::addColumn('repobranch', DataTypes::VARCHAR, 255, true);
		parent::addColumn('validated', DataTypes::INT, false, true);
	}

	public function getName() {

		return $this->getAttr('name');
	}

	public function getHostname() {

		return $this->getAttr('hostname');
	}

	public function getUser() {

		return $this->getAttr('user');
	}

	public function getPassword() {

		return $this->getAttr('password');
	}

	public function getRepopath() {

		return $this->getAttr('repopath');
	}

	/**
	 * @return Repo
	 */
	public function getRepo() {

		//return $this->getAttr('password');
		if ($this->repo == null) {
			$this->repo = new Repo($this->getAttr('repo_id'));
		}
		return $this->repo;
	}

	public function getRepobranch() {

		return $this->getAttr('repobranch');
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
	 * @param string $hostname
	 */
	public function setHostname($hostname) {

		$oldValue = $this->getAttr('hostname');
		if ($oldValue != $hostname) {
			$this->unvalidate();
		}

		$this->setAttr('hostname', $hostname);
	}

	/**
	 *
	 * @param string $user
	 */
	public function setUser($user) {

		$oldValue = $this->getAttr('user');
		if ($oldValue != $user) {
			$this->unvalidate();
		}

		$this->setAttr('user', $user);
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

	/**
	 *
	 * @param string $repopath
	 */
	public function setRepopath($repopath) {

		$oldValue = $this->getAttr('repopath');
		if ($oldValue != $repopath) {
			$this->unvalidate();
		}

		$this->setAttr('repopath', $repopath);
	}

	/**
	 *
	 * @param Repo $repo
	 */
	public function setRepo(Repo $repo) {

		$this->repo = $repo;
		$this->setAttr('repo_id', $repo->getID());
	}

	/**
	 *
	 * @param string $repobranch
	 */
	public function setRepobranch($repobranch) {

		$this->setAttr('repobranch', $repobranch);
	}

	/**
	 * @return bool
	 */
	public function isValidated() {

		return ($this->getAttr('validated') == 1);
	}

	public function validate() {

		$ssh = new Net_SSH2($this->getHostname());
		if (!$ssh->login($this->getUser(), $this->getPassword())) {

			throw new ServerException('Login failed');
		}

		$foundstring = 'found';

//		$repoPathCheckCommand = "if [ -d '".$this->getRepopath().".git' ]; then echo '".$foundstring."'; fi";
		$repoPathCheckCommand = "if [ -d '".$this->getRepopath()."' ]; then echo '".$foundstring."'; fi";
		$shellOutput = $ssh->exec($repoPathCheckCommand);

		if (false === strpos($shellOutput, $foundstring)) {
			throw new ServerException('Repository path cannot be found on the target server');
		}

		$this->setAttr('validated', 1);

	}

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

class ServerException extends Exception {

}