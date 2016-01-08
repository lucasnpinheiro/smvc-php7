<?php

namespace Modules\Test\Models;

class SampleModelInModule extends \Core\Model {

	public function __construct() {
		parent::__construct();
		$this->entity = new \Entities\SampleTable();
	}
	// single entity record
	public function saveSingleRecordInSampleTable($keyColumnData, $anotherColumnData, $oneMoreColumnData) {
		$sample = $this->entity;
		$sample->primary_key_column_name = $keyColumnData;
		$sample->another_column_name = $anotherColumnData;
		$sample->one_more_column_name = $oneMoreColumnData;
		
		$sample->save();
		
		return $sample->primary_key_column_name; // key id, if auto incremented, would automatically be available here
	}

	public function getSingleRecordFromSampleTable($keyColumnData) {
		$sample = $this->entity;
		$sample->get($keyColumnData);
		return $sample;
	}

	public function getMultipleRecordsFromSQLQuery($columnNameData) {
		$sql = "SELECT * FROM sample_table WHERE column_name=? ";
		$this->db->sql($sql, [ $columnNameData]);
		return $this->db->result;
	}
}
