<?php

// Extends to class Reseller
// Only Public methods

require_once SITE_ROOT . '/app/models/ResellerModel.php';

class ResellerController extends Reseller {

	
	//
	public function getUserArray() {
		
		return $this->UserArray();

	}


	//
	public function getInvCodeArray() {

		return $this->invCodeArray();

	}


	//
	public function getInvCodeGen($username) {

		$code = Util::randomCode(20);
		return $this->invCodeGen($code, $username);

	}

}
