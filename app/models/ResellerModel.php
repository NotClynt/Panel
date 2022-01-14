<?php

// Extends to class Database
// Only Protected methods

require_once SITE_ROOT . '/app/core/Database.php';

class Reseller extends Database {
	
	protected function UserArray() {

		// check if user wehere username

			$this->prepare('SELECT * FROM `users` ORDER BY uid ASC');
			$this->statement->execute();

			$result = $this->statement->fetchAll();
			return $result;

		}

	}
	

	// Get array of all invite codes
	protected function invCodeArray() {

		if (Session::isReseller()) {

			$this->prepare('SELECT * FROM `license`');
			$this->statement->execute();

			$result = $this->statement->fetchAll();
			return $result;

		}

	}
	

	// Create invite code
	protected function invCodeGen($code, $createdBy) {

		if (Session::isReseller()) {
			
			$this->prepare('INSERT INTO `license` (`code`, `createdBy`) VALUES (?, ?)');
			$this->statement->execute([$code, $createdBy]);
			
		}

	}
}
