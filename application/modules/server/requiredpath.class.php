<?php

class RequiredPath extends DomainText {


	public function __construct($text=null) {

		if ($text === null || empty($text)) {
			throw new InvalidArgumentException('text-is-empty', 1);
		}

		if (!preg_match('/^\/[a-zA-Z0-9\/\s-_]+\/$/', $text)) {
			throw new InvalidArgumentException('malformed');
		}

		parent::__construct($text);
	}
}