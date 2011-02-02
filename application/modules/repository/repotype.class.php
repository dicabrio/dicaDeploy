<?php

/**
 * For now the supported repository types are modified in this class.
 * Should move it to a configuration file
 * 
 */
class RepoType extends DomainText {

	const GITHUB = 'github';

	private static $supportedRepoType = array(RepoType::GITHUB => 'GitHub');

	public function  __construct($value = null) {
		if (empty($value)) {
			throw new InvalidArgumentException('no-value-give');
		}

		if (!isset(self::$supportedRepoType[$value])) {
			throw new InvalidArgumentException('no-supported-repotype');
		}

		parent::__construct($value);
	}
}


