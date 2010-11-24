<?php
/**
 * This class helps you to connect with a server by SSH. You can interact with it
 * or simply fire one command.
 *
 * Example:
 * $ssh = new SSH2('here.a.hostname.com');
 * $ssh->login('username', 'password');
 * echo $ssh->interactiveExec(array('pwd', // check current directory
 *									'cd ..', // go one directory higher
 *									'pwd' , // check current directory
 *									'cd ~', // go to your home directory
 *									'pwd')); // check current directory
 *
 * @author robert
 */
class SSH2 {

/**
 * @var string
 */
	private $_host;

	/**
	 * @var int
	 */
	private $_port;

	/**
	 * the connection with the server
	 * @var resource
	 */
	private $_connection;

	/**
	 * @var resource
	 */
	private $_currentStream;

	/**
	 * The virtual terminal will be a 'bash|vanilla|xterm'. This should be one of
	 * the terminals defined by the target system. If you want to know which terms your
	 * system supports check: /etc/termcap on your target system.
	 *
	 * @var string
	 */
	private $_defaultTerm = 'bash';

	/**
	 * The width of the virtual terminal window. Every terminal window has a width and a height, so also our
	 * virtual terminal window. This width will allow you to specify the width of the virtual terminal window.
	 * This tells the terminal window the amount of chars that will be placed on the screen before a
	 * newline will follow.
	 *
	 * @var int
	 */
	private $_virtualTermWidth = 132;

	/**
	 * The height of the virtual terminal window.
	 *
	 * @var int
	 */
	private $_virtualTermHeight = 43;

	/**
	 *
	 * @param string $host
	 * @param int $port
	 * @param array $aCallbacks
	 */
	public function __construct($host, $port=22, $aCallbacks = array()) {

		if (!function_exists('ssh2_connect')) {
			throw new SSH2Exception('PECL ssh2 must be installed!');
		}

		$this->validateHost($host);
		$this->validatePort($port);

		$methods = array();
//		$methods = array(
//			'kex' => 'diffie-hellman-group1-sha1',
//			'client_to_server' => array(
//			'crypt' => '3des-cbc',
//			'comp' => 'none'),
//			'server_to_client' => array(
//			'crypt' => 'aes256-cbc,aes192-cbc,aes128-cbc',
//			'comp' => 'none'));

		$this->_host = $host;
		$this->_port = $port;
		$this->_connection = @ssh2_connect($this->_host, $this->_port, $methods, $aCallbacks);
		if ($this->_connection === false) {
			throw new SSH2Exception('Cannot establish a connection with the host '.$this->_host.' on port '.$this->_port);
		}
	}

	/**
	 * @param string $host
	 */
	private function validateHost($host) {
		if (empty($host)) {
			throw new SSH2Exception('No host is given');
		}
	}

	/**
	 * @param int $port
	 */
	private function validatePort($port) {
		if (!is_int($port)) {
			throw new SSH2Exception('Port should be an integer not a '.gettype($port));
		}
	}

	/**
	 * @return resource
	 */
	private function getConnection() {

		if ($this->_connection === null) {
			throw new SSH2Exception('No connection established');
		}

		if ($this->_connection === false) {
			throw new SSH2Exception('No connection with host '.$this->_host.' on port '.$this->_port);
		}

		return $this->_connection;
	}

	/**
	 * Get the fingerprint of the server. You can check if is one of the known hosts
	 *
	 * @return string
	 */
	public function getFingerPrint() {
		return ssh2_fingerprint($this->getConnection(), SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
	}

	/**
	 * Login with given credentials
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function login($username, $password) {
		if (!ssh2_auth_password($this->getConnection(), $username, $password)) {
			throw new SSH2Exception('Cannot login with these credentials');
		}
	}

	/**
	 * execute a simple command. With this method you cannot interact with the server. If you like
	 * to interact with the server by executing multiple commando's use the SSH2::interactiveExec(array);
	 *
	 * @param string $command
	 * @return string
	 */
	public function exec() {

		$argc = func_num_args();
		$argv = func_get_args();

		$command = '';
		for( $i=0; $i<$argc ; $i++) {
			if( $i != ($argc-1) ) {
				$command .= $argv[$i]." && ";
			}else {
				$command .= $argv[$i];
			}
		}
		$this->_currentStream = ssh2_exec($this->getConnection(), $command);
		stream_set_blocking($this->_currentStream, true);
		sleep(1);
		return stream_get_contents($this->_currentStream);
	}

	/**
	 * Write multipe commando's to the server. The output of all commando's is catched and returned.
	 * Note. It could take very long when adding a lot of commando's. This method uses a sleep of a second
	 * after every commando. This is done so we can get the output of the terminal
	 *
	 * @param array $commandos
	 * @param array $env
	 * @return string
	 */
	public function interactiveExec(array $commandos, $env=array()) {

		$this->_currentStream = ssh2_shell($this->getConnection(),
			$this->_defaultTerm,
			$env,
			$this->_virtualTermWidth,
			$this->_virtualTermHeight,
			SSH2_TERM_UNIT_CHARS);

		$sOutput = "";
		stream_set_blocking($this->_currentStream, false);
		foreach ($commandos as $commando) {

			$iWrite = fwrite($this->_currentStream, $commando.';'.PHP_EOL);
			sleep(1);

			$sOutput .= trim(stream_get_contents($this->_currentStream));
		}

		return $sOutput;
	}
}

class SSH2Exception extends Exception {}