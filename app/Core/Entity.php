<?php

namespace Core;

/*
 * Entity = Database table
 */
abstract class Entity extends Model {

	/**
	 * Hold the database connection.
	 *
	 * @var object
	 */
	protected $db;
	// include underscore to prevent possibility of overwriting column names
	public $_tableName, $_idField;
	// data to be fed in
	public $data = [ ];

	/**
	 * Create a new instance of the database helper.
	 */
	public function __construct() {
		/**
		 * connect to PDO here.
		 */
		$this->db = Database::get();
	}

	/**
	 * Table name getter setter
	 */
	public function setTableName(string $tableName) {
		$this->$_tableName = $tableName;
	}

	public function getTableName() {
		return $this->$_tableName;
	}

	/**
	 * ID field setter and getter
	 */
	public function setIDField(string $columnName) {
		$this->_idField = $columnName;
	}

	public function getIDField() {
		return $this->_idField;
	}

	/**
	 * Column names setter and getter
	 */
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		return $this->data[$name];
	}

	/**
	 * Find row based on primary key
	 */
	public function get($idFieldValue) {
		$sql = 'select * from ' . DB_TABLE_PREFIX . $this->_tableName . ' where ' . $this->_idField . ' = ? limit 1';
		$result = $this->db->sql($sql, [ $idFieldValue]);
		
		foreach ($this->db->result->fields as $fieldName) {
			$this->{$fieldName} = $this->db->result->rows[0][$fieldName];
		}
	}

	/**
	 * saving the data
	 */
	public function save() {
		// know the class vars set
		$fieldData = [ ];
		
		$classVars = $this->data;
		foreach ($classVars as $fieldName => $fieldValue) {
			
			$fieldData[$fieldName] = $fieldValue;
		}
		
		// check which statement to run
		if (FALSE != $this->{$this->_idField}) {
			// update
			return $this->db->update($this->_tableName, $fieldData, [ $this->_idField => $this->{$this->_idField}]);
		} else {
			// insert
			$this->{$this->_idField} = $this->db->insert($this->_tableName, $fieldData);
			return $this->{$this->_idField};
		}
	}

	/**
	 * deleting data
	 */
	public function remove($limit = 1) {
		if (is_null($this->{$this->_idField})) {
			return;
		} else {
			$this->delete($this->_table, [ $this->_idField => $this->{$this->_idField}]);
			$sql = "delete ";
			$this->db->sqlQuery($sqlQuery, $params);
		}
	}
}
