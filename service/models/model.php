<?php
require_once dirname(dirname(__FILE__)).'/common/config.php';
require_once dirname(dirname(__FILE__)).'/common/database.php';

class Model
{

	// ===== USERS TABEL ===== //

	public static function add_user($values) {
		return self::_insert('users', $values);
	}

	public static function get_user($values, $sort = 'DESC') {
		return self::_select('users', $values, $sort);
	}

	// ===== SPENDING TABLE ===== //
	public static function add_spending($values) {
		return self::_insert('spending', $values);
	}

	public static function get_spending($values, $sort = 'DESC') {
		return self::_select('spending', $values, $sort);
	}

	public static function delete_spending($id) {
		return self::_delete('fixed_spending', $id);
	}

	// ===== FIXED SPENDING TABLE ===== //
	public static function add_fixed_spending($values) {
		return self::_insert('fixed_spending', $values);
	}

	public static function get_fixed_spending($values, $sort = 'DESC') {
		return self::_select('fixed_spending', $values, $sort);
	}

	public static function delete_fixed_spending($id) {
		return self::_delete('fixed_spending', $id);
	}

	// ===== INCOME TABLE ===== //
	public static function add_income($values) {
		return self::_insert('income', $values);
	}

	public static function get_income($values, $sort = 'DESC') {
		return self::_select('income', $values, $sort);
	}

	public static function delete_income($id) {
		return self::_delete('income', $id);
	}

	// ===== CATEGORY TABLE ===== //
	public static function add_categories($values) {
		return self::_insert('categories', $values);
	}

	public static function get_categories($values, $sort = 'DESC') {
		return self::_select('categories', $values, $sort);
	}

	// ===== PRIVATE METHOD ==== //
	public static function _insert($table, $values) {
		$db = Database::instance();
		$keys = array_keys($values);
		$sql = 'INSERT INTO '.$table.' ('.implode(', ', $keys).') VALUES (:'.implode(', :', $keys).')';

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

	public static function _select($table, $values, $sort) {
		$db = Database::instance();
		$sql = 'SELECT * FROM '.$table;
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

	public static function _delete($table, $id) {
		$db = Database::instance();
		$sql = 'UPDATE '.$table.' SET delete_flag="Y" WHERE id="'.$id.'"';

		$data = array();
		try {
			$data = $db->execute($sql, $values);
		} catch (DatabaseException $e) {
			error_log($e);
		}
		return $data;
	}
}
