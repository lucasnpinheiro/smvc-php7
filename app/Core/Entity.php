<?php
namespace Core;

use Helpers\Database;

abstract class Entity extends Model
{

    /**
     * Hold the database connection.
     *
     * @var object
     */
    protected $db;

    /**
     * Create a new instance of the database helper.
     */
    public function __construct()
    {
        /**
         * connect to PDO here.
         */
        $this->db = Database::get();
    }
}
