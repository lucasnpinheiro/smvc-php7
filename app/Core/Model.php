<?php

/**
 * Model - the base model
 *
 */
namespace Core;

/**
 * Base model class all other models will extend from this base.
 */
abstract class Model {

	/**
	 * Hold the database connection.
	 *
	 * @var object
	 */
	protected $db;

	/**
	 * Create a new instance of the database helper.
	 */
	public function __construct() {
		/**
		 * connect to PDO here.
		 */
		$this->db = \Core\Database::get();
	}
}
