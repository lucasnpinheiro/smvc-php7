<?php

/**
 * Database wrapper on top of PDO
 */
namespace Core;

use PDO;

/**
 * Extending PDO to use custom methods.
 */
class Database extends PDO {

	/**
	 *
	 * @var array Array of saved databases for reusing
	 */
	protected static $instances = [ ];

	/**
	 * Static method get
	 *
	 * @param array $group        	
	 * @return \core\database
	 */
	public static function get($group = false) {
		// Determining if exists or it's not empty, then use default group defined in config
		$group = ! $group ? [ 'type' => DB_TYPE,'host' => DB_HOST,'name' => DB_NAME,'user' => DB_USER,'pass' => DB_PASS] : $group;
		
		// Group information
		$type = $group['type'];
		$host = $group['host'];
		$name = $group['name'];
		$user = $group['user'];
		$pass = $group['pass'];
		
		// ID for database based on the group information
		$id = "$type.$host.$name.$user.$pass";
		
		// Checking if the same
		if (isset(self::$instances[$id])) {
			return self::$instances[$id];
		}
		
		try {
			// I've run into problem where
			// SET NAMES "UTF8" not working on some hostings.
			// Specifiying charset in DSN fixes the charset problem perfectly!
			$instance = new Database("$type:host=$host;dbname=$name;charset=utf8", $user, $pass);
			$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Setting Database into $instances to avoid duplication
			self::$instances[$id] = $instance;
			
			return $instance;
		} catch (PDOException $e) {
			// in the event of an error record the error to ErrorLog.html
			Logger::newMessage($e);
			Logger::customErrorMsg();
		}
	}

	/**
	 * run raw sql queries
	 *
	 * @param string $sql
	 *        	sql command
	 * @return return query
	 */
	public function raw($sql) {
		return $this->query($sql);
	}

	/**
	 * method for selecting records from a database
	 *
	 * @param string $sql
	 *        	sql query
	 * @param array $array
	 *        	named params
	 * @param object $fetchMode        	
	 * @param string $class
	 *        	class name
	 * @return array returns an array of records
	 */
	public function select($sql, $array = [], $fetchMode = PDO::FETCH_OBJ, $class = '') {
		$stmt = $this->prepare($sql);
		foreach ($array as $key => $value) {
			if (is_int($value)) {
				$stmt->bindValue("$key", $value, PDO::PARAM_INT);
			} else {
				$stmt->bindValue("$key", $value);
			}
		}
		
		$stmt->execute();
		
		if ($fetchMode === PDO::FETCH_CLASS) {
			return $stmt->fetchAll($fetchMode, $class);
		} else {
			return $stmt->fetchAll($fetchMode);
		}
	}

	/**
	 * insert method
	 *
	 * @param string $table
	 *        	table name
	 * @param array $data
	 *        	array of columns and values
	 */
	public function insert($table, $data) {
		$fieldNames = implode(', ', array_keys($data));
		$fieldValues = ':' . implode(', :', array_keys($data));
		$stmt = $this->prepare("INSERT INTO " . DB_TABLE_PREFIX . ".$table ($fieldNames) VALUES ($fieldValues)");
		
		foreach ($data as $key => $value) {
			$stmt->bindValue(":$key", $value);
		}
		
		$stmt->execute();
		return $this->lastInsertId();
	}

	/**
	 * update method
	 *
	 * @param string $table
	 *        	table name
	 * @param array $data
	 *        	array of columns and values
	 * @param array $where
	 *        	array of columns and values
	 */
	public function update($table, $data, $where) {
		ksort($data);
		
		$fieldDetails = null;
		foreach ($data as $key => $value) {
			$fieldDetails .= "$key = :field_$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');
		
		$whereDetails = null;
		$i = 0;
		foreach ($where as $key => $value) {
			if ($i == 0) {
				$whereDetails .= "$key = :where_$key";
			} else {
				$whereDetails .= " AND $key = :where_$key";
			}
			$i ++;
		}
		$whereDetails = ltrim($whereDetails, ' AND ');
		
		$stmt = $this->prepare("UPDATE " . DB_TABLE_PREFIX . "$table SET $fieldDetails WHERE $whereDetails");
		
		foreach ($data as $key => $value) {
			$stmt->bindValue(":field_$key", $value);
		}
		
		foreach ($where as $key => $value) {
			$stmt->bindValue(":where_$key", $value);
		}
		
		$stmt->execute();
		return $stmt->rowCount();
	}

	/**
	 * Delete method
	 *
	 * @param string $table
	 *        	table name
	 * @param array $where
	 *        	array of columns and values
	 * @param integer $limit
	 *        	limit number of records
	 */
	public function delete($table, $where, $limit = 1) {
		ksort($where);
		
		$whereDetails = null;
		$i = 0;
		foreach ($where as $key => $value) {
			if ($i == 0) {
				$whereDetails .= "$key = :$key";
			} else {
				$whereDetails .= " AND $key = :$key";
			}
			$i ++;
		}
		$whereDetails = ltrim($whereDetails, ' AND ');
		
		// if limit is a number use a limit on the query
		if (is_numeric($limit)) {
			$uselimit = "LIMIT $limit";
		}
		
		$stmt = $this->prepare("DELETE FROM " . DB_TABLE_PREFIX . ".$table WHERE $whereDetails $uselimit");
		
		foreach ($where as $key => $value) {
			$stmt->bindValue(":$key", $value);
		}
		
		$stmt->execute();
		return $stmt->rowCount();
	}

	/**
	 * truncate table
	 *
	 * @param string $table
	 *        	table name
	 */
	public function truncate($table) {
		return $this->exec("TRUNCATE TABLE " . DB_TABLE_PREFIX . ".$table");
	}

	/**
	 * execute sql query and fetch result automatically
	 */
	public function sql($sqlQuery, $paramsArray = [], $extractResult = false) {
		$this->result = new \Helpers\Datasource();
		\Helpers\Watchlist::add($sqlQuery . '<br />Params: ' . print_r($paramsArray, true), 'Query @ ' . microtime() . ': ');
		
		$this->sql = $sqlQuery;
		
		$statement = $this->prepare($sqlQuery);
		foreach ($paramsArray as $key => $value) {
			if (is_int($key)) {
				$key = $key + 1;
			}
			if (is_int($value)) {
				$statement->bindValue("$key", $value, PDO::PARAM_INT);
			} else {
				$statement->bindValue("$key", $value);
			}
		}
		
		$statement->execute();
		
		if ($statement->error != '') {
			$this->error = $statement->error;
			$status = false;
			\Helpers\Watchlist::add('<span style="color:#FF0000;">' . $this->error . '</span>', 'SQL Query error: ');
		} else {
			// know the type of sql
			$sqlType = $this->result->getSQLType($this->sql);
			
			// whether to extrack result or not
			if (in_array($sqlType, [ 'show','select','exec'])) {
				$extractResult = true;
			}
			
			// if there is result, extract it
			if (true == $extractResult) {
				// get the data
				while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
					$rowValues = [ ];
					foreach ($row as $key => $value) {
						$rowValues[$key] = $value;
					}
					$this->result->rows[] = $rowValues;
				}
			} else {
				
				// whether affected rows comes or not
				if (in_array($sqlType, [ 'insert','delete','update','truncate','use'])) {
					$this->result->affectedRows = $statement->rowCount();
					if ($this->_sqlType == 'insert') // get last insert if for insert statements
						$this->result->lastInsertId = $this->lastInsertId();
				}
			}
			$this->result->prepare();
			
			$status = true;
		}
		
		return $status;
	}
}
