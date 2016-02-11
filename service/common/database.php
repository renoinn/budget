<?php
require_once dirname(__FILE__).'/config.php';

class DatabaseException extends Exception {
}

class Database
{
	public static $instance = null;

	protected $_connection;

	public static function instance()
	{
		if (!isset(static::$instance))
		{
			new Database();
		}
		return static::$instance;
	}

	protected function __construct()
	{
		static::$instance = $this;
	}

	final public function __destruct()
	{
		$this->disconnect();
	}

	public function connect()
	{
		$this->_connection = new PDO(PDO_DNS, DATABASE_USER, DATABASE_PASSWARD);
		$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function disconnect()
	{
		$this->_connection = null;

		return true;
	}

	public function execute($sql, $values)
	{
		$this->_connection or $this->connect();
		// 値がない場合はそのまま実行する
		if ($values === null) {
			$count = $this->_connection->exec($sql);
			return $count;
		}

		
		try {
			$sth = $this->_connection->prepare($sql);
		} catch (PDOException $e) {
			error_log($e->getMessage());
			throw new DatabaseException('fail prepare.'.$sql);
		}
		if (!$sth->execute($values)) {
			throw new DatabaseException('insert faild. sql:'.$sth->queryString);
		}

		$type = strtoupper(substr(ltrim($sql,'('), 0, 6));
		$result = null;
		switch($type)
		{
			case 'SELECT':
				$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
				break;
			case 'INSERT':
			case 'CREATE':
				/*
				return array(
					$this->_connection->lastInsertId(),
					$this->_connection->rowCount(),
				);
				*/
				return $this->_connection->lastInsertId();
				break;
			case 'UPDATE':
				return $sth->rowCount();
				break;
			default:
				break;
		}
		return $result;
	}

	/**
	 * Start a transaction
	 *
	 * @return bool
	 */
	public function start_transaction()
	{
		$this->_connection or $this->connect();
		return $this->_connection->beginTransaction();
	}

	/**
	 * Commit a transaction
	 *
	 * @return bool
	 */
	public function commit_transaction()
	{
		return $this->_connection->commit();
	}

	/**
	 * Rollback a transaction
	 * @return bool
	 */
	public function rollback_transaction()
	{
		return $this->_connection->rollBack();
	}
}
