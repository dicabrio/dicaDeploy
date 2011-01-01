<?php

class Host extends DomainText {

	public function __construct($sValue) {
		parent::__construct($sValue, 3, 255);
	}

}