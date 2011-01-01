<?php

class SnapshotManager {

	private $snapshotsLocation;

	private $allowedDatabaseConfiguration;

	/**
	 * @todo validate the location and the array of the configuration
	 * 
	 * @param string $snapshotsLocation
	 * @param array $allowedDatabaseConfiguration (name => value)
	 */
	public function __construct($snapshotsLocation, $allowedDatabaseConfiguration) {

		$this->snapshotsLocation = $snapshotsLocation;
		$this->allowedDatabaseConfiguration = $allowedDatabaseConfiguration;
		
	}

	/**
	 *
	 * @param Database $database
	 */
	public function create(Database $database, $whereToSave = null) {
		if ($whereToSave == null) {
			$whereToSave = $this->snapshotsLocation;
		}

		$databaseConfiguration = $this->allowedDatabaseConfiguration[$database->getType()];

		$name = $database->getName();
		$snapshot = $whereToSave.$name.'_'.time().'_a0_.sql';
		$sToExecute	= $databaseConfiguration.'mysqldump -u '.$database->getUser().' --password='.$database->getPassword().' -h '.$database->getHost().' '.$database->getName().' > '.$snapshot;
		
		$output = shell_exec($sToExecute);

		$snapshot = new FileManager($snapshot);
		return new Snapshot($database, $snapshot);
	}

	/**
	 *
	 * @param Database $database
	 */
	public function search(Database $database) {

		$databaseName = $database->getName();
		$snapshots = array();
		$snapshotFiles = glob($this->snapshotsLocation.$databaseName.'_*.sql');
		foreach ($snapshotFiles as $snapshot) {
			try {
				$snapshots[] = new Snapshot($database, new FileManager($snapshot));
			} catch (SnapshotException $e) {
				// not valid.. let it be
			}
		}

		return array_reverse($snapshots);

	}

	/**
	 *
	 * @param Database $database
	 * @param string $snapshotfile
	 */
	public function restore(Database $database, $snapshotfile) {

		$snapshot = $this->get($database, $snapshotfile);

		$databaseConfiguration = $this->allowedDatabaseConfiguration[$database->getType()];

		$sToExecute	= $databaseConfiguration.'mysql -u '.$database->getUser().' --password='.$database->getPassword().' -h '.$database->getHost().' --database='.$database->getName().' < '.$snapshot->getFile()->getFullPath();
		$output = shell_exec($sToExecute);

	}

	/**
	 *
	 * @param string $snapshotFile
	 */
	public function delete($snapshotFile) {

		$fileManager = new FileManager($this->snapshotsLocation.$snapshotFile);
		$fileManager->delete();

	}

	/**
	 *
	 * @param Database $database
	 * @param sting $snapshotFile
	 * @return Snapshot
	 */
	public function get(Database $database, $snapshotFile) {

		return new Snapshot($database, new FileManager($this->snapshotsLocation.$snapshotFile));

	}

}

/**
 *
 */
class Snapshot {

	/**
	 *
	 * @var Database
	 */
	private $database;

	/**
	 *
	 * @var File
	 */
	private $snapshot;

	/**
	 *
	 * @var string
	 */
	private $timeOfCreation;

	/**
	 *
	 * @var string
	 */
	private $label;

	/**
	 *
	 * @param string $dbname
	 * @param string $location
	 * @param string $snapshot
	 */
	public function __construct(Database $dbname, FileManager $snapshot) {

		$this->database = $dbname;
		$this->processSnapshotFilename($snapshot);
		$this->snapshot = $snapshot;

	}

	/**
	 *
	 * @param string $snapshot
	 */
	private function processSnapshotFilename(FileManager $snapshot) {

		$pattern = '/'.$this->database->getName().'_(\d+)_a0_([a-zA-Z0-9-_]*)\.sql$/';
		if (!preg_match($pattern, $snapshot->getFilename(), $matches)) {
			throw new SnapshotException('The given snapshot is not valid. Perhaps it is of another database: '. $snapshot->getFilename());
		}

		$this->timeOfCreation = $matches[1];
		$this->label = $matches[2];

	}

	/**
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 *
	 * @return FileManager
	 */
	public function getFile() {

		return $this->snapshot;
		
	}

	/**
	 *
	 * @return string
	 */
	public function getTimeOfCreation() {

		return $this->timeOfCreation;
		
	}

	/**
	 *
	 * @param string $title
	 */
	public function addLabel($title) {
		$title = str_replace(array(' ', '_'), '', trim($title));
		$this->snapshot->moveTo($this->snapshot->getPath(), $this->database->getName().'_'.$this->timeOfCreation.'_a0_'.$title.'.sql');
	}

}

class SnapshotException extends Exception {}