<?php
require_once dirname(dirname(__FILE__)).'/common/config.php';
require_once dirname(dirname(__FILE__)).'/common/database.php';

class Model
{

	// ===== USERS TABEL ===== //

	public static function add_user($values) {
		$db = Database::instance();
		$keys = array_keys($values);
		$sql = 'INSERT INTO users ('.implode(', ', $keys).') VALUES (:'.implode(', :', $keys).')';

		$id = '';
		$db->start_transaction();
		try {
			$id = $db->execute($sql, $values);
			$db->commit_transaction();
		} catch (DatabaseException $e) {
			error_log($e);
			$db->rollback_transaction();
			return false;
		}
		return $id;
	}

	public static function get_user($values, $sort = 'DESC') {
		$db = Database::instance();
		$sql = 'SELECT * FROM users';
		$keys = array_keys($values);
		if (0 < count($keys)) {
			$where = ' WHERE ';
			for($i = 0; $i < count($keys); $i++) {
				if ($i != 0) $where .= ' AND ';
				$where .= $keys[$i].'=:'.$keys[$i];
			}
			$sql .= $where;
		}
		$sql .= ' ORDER BY created '.$sort;

		$data = array();
		try {
			$data = $db->execute($sql, $values);
		} catch (DatabaseException $e) {
			error_log($e);
		}
		return $data;
	}
}
