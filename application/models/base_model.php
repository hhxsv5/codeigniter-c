<?php
/**
 * Base class of Model
 * Implemented some common functions
 *
 * @author XieBiao <hhxsv5@sina.com>
 */
abstract class Base_model extends CI_Model
{
	const FIELD_ID = 'id';

	public function __construct()
	{
		$this -> load -> database();
		parent::__construct();
	}

	/**
	 * Must implements this function to tell parent class your table name
	 */
	abstract protected function getTableName();

	/**
	 * Get query objects by condition
	 *
	 * @param array $select        	
	 * @param array $where        	
	 * @param array $orderby        	
	 * @param array $limit        	
	 * @param string $className        	
	 */
	public function getAsObjects(array $select = NULL, array $where = NULL, array $orderby = NULL, array $limit = NULL, $className = NULL)
	{
		if (!empty($select)) {
			$this -> db -> select(implode(',', $select));
		}
		
		if (is_array($where)) {
			foreach ($where as $key => $value) {
				if(is_array($value)){
					$this -> db -> where_in($key, $value);
				}else{
					$this -> db -> where($key, $value);
				}
			}
		}
		
		if (is_array($orderby)) {
			foreach ($orderby as $key => $value) {
				$this -> db -> order_by($key, $value);
			}
		}
		
		if (is_array($limit)) {
			$count = count($limit);
			if ($count === 1) {
				if (isset($limit[0]) && is_numeric($limit[0])) {
					$this -> db -> limit($limit[0]);
				}
			} elseif ($count >= 2) {
				if (isset($limit[0]) && isset($limit[1])) {
					if (is_numeric($limit[0]) && is_numeric($limit[1])) {
						$this -> db -> limit($limit[1], $limit[0]);
					}
				}
			}
		}
		if (is_string($className)) {
			return $this -> db -> get($this -> getTableName()) -> result($className);
		} else {
			return $this -> db -> get($this -> getTableName()) -> result();
		}
	}

	/**
	 * Get query arrays by condition
	 *
	 * @param array $select        	
	 * @param array $where        	
	 * @param array $orderby        	
	 * @param array $limit        	
	 */
	public function getAsArrays(array $select = NULL, array $where = NULL, array $orderby = NULL, array $limit = NULL)
	{
		if (!empty($select)) {
			$this -> db -> select(implode(',', $select));
		}
		
		if (is_array($where)) {
			foreach ($where as $key => $value) {
				if(is_array($value)){
					$this -> db -> where_in($key, $value);
				}else{
					$this -> db -> where($key, $value);
				}
			}
		}
		
		if (is_array($orderby)) {
			foreach ($orderby as $key => $value) {
				$this -> db -> order_by($key, $value);
			}
		}
		
		if (is_array($limit)) {
			$count = count($limit);
			if ($count === 1) {
				if (isset($limit[0]) && is_numeric($limit[0])) {
					$this -> db -> limit($limit[0]);
				}
			} elseif ($count >= 2) {
				if (isset($limit[0]) && isset($limit[1])) {
					if (is_numeric($limit[0]) && is_numeric($limit[1])) {
						$this -> db -> limit($limit[1], $limit[0]);
					}
				}
			}
		}
		return $this -> db -> get($this -> getTableName()) -> result_array();
	}

	/**
	 * Get one query result as object by condition
	 *
	 * @param array $select        	
	 * @param array $where        	
	 * @param string $className        	
	 */
	public function getOneAsObject(array $select = NULL, array $where = NULL, $className = NULL)
	{
		if (!empty($select)) {
			$this -> db -> select(implode(',', $select));
		}
		
		if (is_string($className)) {
			return $this -> db -> get_where($this -> getTableName(), $where, 1) -> row(NULL, $className);
		} else {
			return $this -> db -> get_where($this -> getTableName(), $where, 1) -> row();
		}
	}

	/**
	 * Get one query result as array by condition
	 *
	 * @param array $select        	
	 * @param array $where        	
	 */
	public function getOneAsArray(array $select = NULL, array $where = NULL)
	{
		if (!empty($select)) {
			$this -> db -> select(implode(',', $select));
		}
		
		return $this -> db -> get_where($this -> getTableName(), $where, 1) -> row_array();
	}

	/**
	 * Get one query result by id as object
	 *
	 * @param int $id        	
	 * @param array $select        	
	 * @param string $className        	
	 */
	public function getObjectById($id, array $select = NULL, $className = NULL)
	{
		return $this -> getOneAsObject($select, array (
				self::FIELD_ID => $id
		), $className);
	}

	/**
	 * Get one query result by id as array
	 *
	 * @param int $id        	
	 * @param array $select        	
	 */
	public function getArrayById($id, array $select = NULL)
	{
		return $this -> getOneAsArray($select, array (
				self::FIELD_ID => $id
		));
	}

	/**
	 * Add one record to DB from array or object
	 *
	 * @param array|object $data
	 *        	key=>value array or member property=>value object
	 */
	public function addOne($data)
	{
		return $this -> db -> insert($this -> getTableName(), $data);
	}

	/**
	 * Batch add records to DB from two-dimension array
	 *
	 * @param array $data
	 *        	two-dimension
	 *        	key=>value array
	 */
	public function addBatch(array $data)
	{
		return $this -> db -> insert_batch($this -> getTableName(), $data);
	}

	/**
	 * Update records by condition
	 *
	 * @param array $data        	
	 * @param array $where|NULL        	
	 */
	public function update(array $data, array $where = NULL)
	{
		return $this -> db -> update($this -> getTableName(), $data, $where);
	}

	/**
	 * Delete records by condition
	 *
	 * @param array $where|NULL        	
	 */
	public function delete(array $where = NULL)
	{
		return $this -> db -> delete($this -> getTableName(), $where);
	}

	/**
	 * Get the count by condition
	 *
	 * @param array $where        	
	 */
	public function count(array $where = NULL)
	{
		if (is_array($where)) {
			foreach ($where as $key => $value) {
				if(is_array($value)){
					$this -> db -> where_in($key, $value);
				}else{
					$this -> db -> where($key, $value);
				}
			}
		}
		$this -> db -> from($this -> getTableName());
		return $this -> db -> count_all_results();
	}
	
	public function increase($field, $number, $where = NULL, $and = TRUE)
	{
		$whereSQL = '';
		if(is_array($where)){
			$count = count($where);
			$i = 0;
			foreach ($where as $key => $value) {
				if(is_numeric($value)){
					$value = floatval($value);
				}else{
					$value = "'{$value}'";
				}
				if($this -> db -> _has_operator($key)){
					$whereSQL .= "{$key}{$value}";
				}else{
					$whereSQL .= "`{$key}`={$value}";
				}
				++$i;
				if($i != $count){
					if($and) {
						$whereSQL .= ' AND ';
					}else{
						$whereSQL .= ' OR ';
					}
				}
			}
		}elseif (is_string($where)){
			$whereSQL = $where;
		}
	
		$sql = "UPDATE `{$this->getTableName()}` SET `{$field}`=`{$field}`+{$number}";

		if($whereSQL != ''){
			$sql .= " WHERE {$whereSQL}";
		}else{
			$sql .= " WHERE 1";
		}
		
		return $this -> db -> query($sql);
	}
	
	public function decrease($field, $number, $where = array())
	{
		$whereSQL = '';
		if(is_array($where)){
			foreach ($where as $key => $value) {
				if(is_numeric($value)){
					$value = floatval($value);
				}else{
					$value = "'{$value}'";
				}
				if($this -> db -> _has_operator($key)){
					$whereSQL .= "{$key}{$value}";
				}else{
					$whereSQL .= "`{$key}`={$value}";
				}
			}
		}elseif (is_string($where)){
			$whereSQL = $where;
		}
	
		$sql = "UPDATE `{$this->getTableName()}` SET `{$field}`=`{$field}`-{$number}";
	
		if($whereSQL != ''){
			$sql .= " WHERE {$whereSQL}";
		}else{
			$sql .= " WHERE 1";
		}
		
		return $this -> db -> query($sql);
	}

	public function transactionBegin()
	{
		return $this -> db -> trans_begin();
	}

	public function transactionCommit()
	{
		return $this -> db -> trans_commit();
	}

	public function transactionRollback()
	{
		return $this -> db -> trans_rollback();
	}

	public function transactionStatus()
	{
		return $this -> db -> trans_status();
	}
}