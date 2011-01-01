<?php

class RepositoryController extends CmsController {

	const CONTROLLER = 'repository';

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

		parent::__construct(self::CONTROLLER.'/'.$method, Lang::get('repository.title'));
		$this->session = Session::getInstance();
	}

	/**
	 * @return View
	 */
	public function _index() {

		$repos = Repo::findAll();

		$view = new View(Conf::get('general.dir.templates') . '/repository/repositoryoverview.php');
		$view->assign('repos', $repos);

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
	private function buildRepositoryEditForm(Repo $repo) {

		$name = new Input('text', 'name', $repo->getName());
		$location = new Input('text', 'location', $repo->getLocation());
		$username = new Input('text', 'username', $repo->getUsername());
		$password = new Input('text', 'password', $repo->getPassword());
		$button = new ActionButton('save');

		if ($this->form == null) {
			$this->form = new Form(Conf::get('general.url.www').'/'.self::CONTROLLER . '/save/' . $repo->getID(), Request::POST, 'editServer');
			$this->form->addFormElement($name);
			$this->form->addFormElement($location);
			$this->form->addFormElement($username);
			$this->form->addFormElement($password);
			$this->form->addFormElement($button);
		}

		return $this->form;
	}

	public function edit() {

		$repo = new Repo(Util::getUrlSegment(2));

		$view = new View(Conf::get('general.dir.templates') . '/repository/editrepository.php');
		$view->assign('errors', $this->session->get('errors'));
		$view->assign('form', $this->buildRepositoryEditForm($repo));

		$this->session->set('errors', null);

		$baseView = parent::getBaseView();
		$baseView->assign('oModule', $view);

		return $baseView;
	}

	public function save() {

		$repo = new Repo(Util::getUrlSegment(2));
		$data = DataFactory::getInstance();

		$form = $this->buildRepositoryEditForm($repo);
		$form->listen(Request::getInstance());

		$mapper = new FormMapper();
		$mapper->addFormElementToDomainEntityMapping('name', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('location', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('username', 'RequiredTextLine');
		$mapper->addFormElementToDomainEntityMapping('password', 'RequiredTextLine');

		try {

			$data->beginTransaction();

			$mapper->constructModelsFromForm($form);
			
			$repo->setName($mapper->getModel('name'));
			$repo->setLocation($mapper->getModel('location'));
			$repo->setUsername($mapper->getModel('username'));
			$repo->setPassword($mapper->getModel('password'));
			$repo->save();

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

			$repo = new Repo(Util::getUrlSegment(2));
			$repo->delete();

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

			$repo = new Repo(Util::getUrlSegment(2));
			$repo->validate();
			$repo->save();

			$data->commit();

		} catch (Exception $e) {

			$data->rollBack();
			$this->session->set('errors', array($e->getMessage()));

		}

		$this->_redirect(self::CONTROLLER);
		
	}

}
