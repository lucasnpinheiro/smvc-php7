<?php

namespace Entities;

class Sample extends \Core\Entity {

	public function __construct() {
		parent::__construct();
		
		$this->_tableName = 'sample_table';
		
		$this->_idField = 'primary_key_column_name';
	}
}
