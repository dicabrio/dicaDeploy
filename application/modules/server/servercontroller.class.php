<?php

class ServerController extends CmsController {
	
	const CONTROLLER = 'server';

	/**
	 * @var Session
	 */
	private $session;
	/**
	 *
	 * @var Form
	 */
	private $form;

	/**
	 * @param string $method
	 */
	public function __construct($method) {

		parent::__construct(self::CONTROLLER . '/' . $method, Lang::get('server.title'));
		$this->session = Session::getInstance();
	}

	/**
	 * @return View
	 */
	public function _index() {

		$servers = Server::findAll();

		$view = new View(Conf::get('general.dir.templates') . '/server/serveroverview.php');
		$view->assign('errors', $this->session->get('errors'));
		$view->assign('servers', $servers);
		
		$this->session->set('errors', null);

		$baseView = parent::getBaseView();
		$baseView->assign('oModule', $view);

		return $baseView;
	}

	public function _default() {
		return 'Not implemented yet!';
	}

	/**
	 * @return Form
	 */
	private function buildRepositoryEditForm(Server $server) {

		$name = new Input('text', 'name', $server->getName());
		$hostname = new Input('text', 'hostname', $server->getHostname());
		$username = new Input('text', 'user', $server->getUser());
		$password = new Input('text', 'password', $server->getPassword());
		$repopath = new Input('text', 'repopath', $server->getRepopath());

		$repos = Repo::findAll();
		$repo_id = new Select('repo_id');
		$repo_id->setValue($server->getRepo()->getID());
		foreach ($repos as $repo) {
			$repo_id->addOption($repo->getID(), $repo->getName());
		}
		$repobranch = new Input('text', 'repobranch', $server->getRepobranch());
		$button = new ActionButton('save');

		if ($this->form == null) {
			$this->form = new Form(Conf::get('general.url.www').'/'.self::CONTROLLER.'/save/'.$server->getID(), Request::POST, 'editRepository');
			$this->form->addFormElement($name->getName(), $name);
			$this->form->addFormElement($hostname->getName(), $hostname);
			$this->form->addFormElement($username->getName(), $username);
			$this->form->addFormElement($password->getName(), $password);
			$this->form->addFormElement($repopath->getName(), $repopath);
			$this->form->addFormElement($repo_id->getName(), $repo_id);
			$this->form->addFormElement($repobranch->getName(), $repobranch);
			$this->form->addFormElement('save', $button);
		}

		return $this->form;
	}
	

	public function edit() {

		try {
			
			$server = new Server(Util::getUrlSegment(2));

			$view = new View(Conf::get('general.dir.templates') . '/server/editserver.php');
			$view->assign('errors', $this->session->get('errors'));
			$view->assign('form', $this->buildRepositoryEditForm($server));

			$this->session->set('errors', null);

			$baseView = parent::getBaseView();
			$baseView->assign('oModule', $view);
			
			return $baseView;

		} catch (RecordException $e) {
			$this->session->set('errors', array('record-not-found'));
			$this->_redirect(self::CONTROLLER);
		}
	}

	public function save() {

		$data = DataFactory::getInstance();
		$server = new Server(Util::getUrlSegment(2));

		$form = $this->buildRepositoryEditForm($server);
		$form->listen(Request::getInstance());

		$mapper = new FormMapper();
		$mapper->addFormElementToDomainEntityMapping('name', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('hostname', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('user', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('password', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('repopath', 'RequiredPath');
		$mapper->addFormElementToDomainEntityMapping('repo_id', 'Repo');
		$mapper->addFormElementToDomainEntityMapping('repobranch', 'RequiredTextLine');

		try {

			$data->beginTransaction();

			$mapper->constructModelsFromForm($form);

			$server->setName($mapper->getModel('name'));
			$server->setHostname($mapper->getModel('hostname'));
			$server->setUser($mapper->getModel('user'));
			$server->setPassword($mapper->getModel('password'));
			$server->setRepopath($mapper->getModel('repopath'));
			$server->setRepo($mapper->getModel('repo_id'));
			$server->setRepobranch($mapper->getModel('repobranch'));
			$server->save();

			$data->commit();

			$this->_redirect(self::CONTROLLER);
		} catch (FormMapperException $e) {

			$data->rollBack();

			$this->session->set('errors', $mapper->getMappingErrors());
			return $this->edit();
		}
	}

	public function delete() {

		$data = DataFactory::getInstance();
		try {

			$data->beginTransaction();

			$server = new Server(Util::getUrlSegment(2));
			$server->delete();

			$data->commit();

		} catch (Exception $e) {

			$data->rollBack();
			$this->session->set('errors', array($e->getMessage()));
			
		}

		$this->_redirect(self::CONTROLLER);

	}

	public function validate() {

		$data = DataFactory::getInstance();

		try {

			$data->beginTransaction();

			$server = new Server(Util::getUrlSegment(2));
			$server->validate();
			$server->save();

			$data->commit();

		} catch (Exception $e) {

			$data->rollBack();
			$this->session->set('errors', array($e->getMessage()));

		}

		$this->_redirect(self::CONTROLLER);
	}

}
