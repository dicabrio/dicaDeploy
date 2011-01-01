<?php

/**
 * 
 */
class SnapshotController extends CmsController {

	const CONTROLLER = 'snapshot';

	/**
	 * @var Session
	 */
	private $session;

	/**
	 *
	 * @var Request
	 */
	private $request;

	/**
	 * @param string $method
	 */
	public function __construct($method) {

		parent::__construct(self::CONTROLLER.'/'.$method, Lang::get(self::CONTROLLER.'.title'));
		$this->session = Session::getInstance();
		$this->snapshotManager = new SnapshotManager(Conf::get('snapshot.snapshotlocation'), Conf::get('snapshot.supporteddatabases'));

	}

	public function _index() {

		$records = Database::findAll();
		$snapshots = array();
		foreach ($records as $record) {
			$snapshots[$record->getName()] = $this->snapshotManager->search($record);
		}

		$view = new View(Conf::get('general.dir.templates') . '/'.self::CONTROLLER.'/'.self::CONTROLLER.'index.php');
		$view->assign('error', $this->session->get('error'));
		$view->assign('records', $records);
		$view->assign('snapshots', $snapshots);
		
		$this->session->set('error', null);

		$baseView = parent::getBaseView();
		$baseView->assign('oModule', $view);

		return $baseView;

	}

	public function _default() {
		return 'boo!';
	}

	public function setArguments($arguments) {
		$this->arguments = $arguments;
	}

	/**
	 *
	 */
	public function create() {

		try {

			$database = new Database(Util::getUrlSegment(2));
			$this->snapshotManager->create($database);

		} catch (Exception $e) {
			$this->session->set('error', $e->getMessage());
		}

		$this->_redirect('/snapshot/#' . $database->getName());
	}

	public function restore() {

		try {

			$database = new Database(Util::getUrlSegment(2));
			$this->snapshotManager->restore($database, Util::getUrlSegment(3));

		} catch (Exception $e) {
			$this->session->set('error', $e->getMessage());
		}

		$this->_redirect('/snapshot/#' . $database->getName());
	}

	public function delete() {

		try {

			$database = new Database(Util::getUrlSegment(2));
			$this->snapshotManager->delete(Util::getUrlSegment(3));

		} catch (Exception $e) {
			$this->session->set('error', $e->getMessage());
		}

		$this->_redirect('/snapshot/#' . $database->getName());
	}

	public function rename() {

		if (Request::method() != Request::POST) {
			$this->_redirect('/snapshot/');
		}

		try {

			$request = Request::getInstance();
			$newTitle = $request->post('renameto');

			$database = new Database(Util::getUrlSegment(2));
			$snapshot = $this->snapshotManager->get($database, Util::getUrlSegment(3));
			$snapshot->addLabel($newTitle);


		} catch (Exception $e) {

			$this->session->set('error', $e->getMessage());
			
		}

		$this->_redirect('/snapshot/#' . $database->getName());

	}

	/**
	 * download the snapshot
	 */
	public function download() {
		try {

			$database = new Database(Util::getUrlSegment(2));

			$requestedSnapshotName = Util::getUrlSegment(3);
			if (false !== strpos($requestedSnapshotName, '..')) {
				$this->session->set('error', 'You are not allowed to download this file');
				Util::gotoPage(Conf::get('general.url.www') . '/snapshot/#' . $database->getName());
			}

			$snapshot = $this->snapshotManager->get($database, $requestedSnapshotName);
			$file = $snapshot->getFile();

			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $file->getFilename());
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file->getFullPath()));

			return $file->getContents();

		} catch (Exception $e) {
			$this->session->set('error', $e->getMessage());
		}

		$this->_redirect('/snapshot/#' . $database->getName());
	}

}