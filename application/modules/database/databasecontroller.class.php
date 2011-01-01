<?php

class DatabaseController extends CmsController {

	const CONTROLLER = 'database';

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

		parent::__construct(self::CONTROLLER.'/'.$method, Lang::get(self::CONTROLLER.'.title'));
		$this->session = Session::getInstance();
		
	}

	/**
	 * @return View
	 */
	public function _index() {

		$records = Database::findAll();

		$view = new View(Conf::get('general.dir.templates') . '/'.self::CONTROLLER.'/'.self::CONTROLLER.'index.php');
		$view->assign('records', $records);

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
	private function buildEditForm(Database $record) {

		$type = new Select('type', $record->getType());
		$type->addOption('mysql', 'MySQL');

		$host = new Input('text', 'host', $record->getHost());
		$user = new Input('text', 'user', $record->getUser());
		$password = new Input('text', 'password', $record->getPassword());
		$name = new Input('text', 'name', $record->getName());

		$button = new ActionButton('save');

		if ($this->form == null) {
			$this->form = new Form(Conf::get('general.url.www').'/'.self::CONTROLLER . '/save/' . $record->getID(), Request::POST);
			$this->form->addFormElement($type);
			$this->form->addFormElement($host);
			$this->form->addFormElement($user);
			$this->form->addFormElement($password);
			$this->form->addFormElement($name);
			$this->form->addFormElement($button);
		}

		return $this->form;
	}

	/**
	 *
	 * @return View
	 */
	public function edit() {

		$record = new Database(Util::getUrlSegment(2));

		$view = new View(Conf::get('general.dir.templates') . '/'.self::CONTROLLER.'/'.self::CONTROLLER.'edit.php');
		$view->assign('errors', $this->session->get('errors'));
		$view->assign('form', $this->buildEditForm($record));

		$this->session->set('errors', null);

		$baseView = parent::getBaseView();
		$baseView->assign('oModule', $view);

		return $baseView;
	}

	/**
	 *
	 * @return View
	 */
	public function save() {

		$record = new Database(Util::getUrlSegment(2));
		$data = DataFactory::getInstance();

		$form = $this->buildEditForm($record);
		$form->listen(Request::getInstance());

		$mapper = new FormMapper();
		$mapper->addFormElementToDomainEntityMapping('type', 'DatabaseType');
		$mapper->addFormElementToDomainEntityMapping('host', 'Host');
		$mapper->addFormElementToDomainEntityMapping('user', 'Username');
		$mapper->addFormElementToDomainEntityMapping('password', 'Password');
		$mapper->addFormElementToDomainEntityMapping('name', 'RequiredTextLine');

		try {

			$data->beginTransaction();

			$mapper->constructModelsFromForm($form);

			$record->setType($mapper->getModel('type'));
			$record->setHost($mapper->getModel('host'));
			$record->setUser($mapper->getModel('user'));
			$record->setPassword($mapper->getModel('password'));
			$record->setName($mapper->getModel('name'));
			$record->save();

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

			$record = new Database(Util::getUrlSegment(2));
			$record->delete();

			$data->commit();

		} catch (Exception $e) {

			$data->rollBack();
			$this->session->set('errors', array($e->getMessage()));

		}

		$this->_redirect(self::CONTROLLER);

	}

//	public function validate() {
//
//		$data = DataFactory::getInstance();
//
//		try {
//
//			$data->beginTransaction();
//
//			$record = new Database(Util::getUrlSegment(2));
//			$record->validate();
//			$record->save();
//
//			$data->commit();
//
//		} catch (Exception $e) {
//
//			$data->rollBack();
//			$this->session->set('errors', array($e->getMessage()));
//
//		}
//
//		$this->_redirect(self::CONTROLLER);
//
//	}

}
