<?php

/**
 * Base model
 * @author Dave Xie <hhxsv5@sina.com>
 */
class MY_Model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        
        parent::__construct();
    }

    /**
     * Get the table name
     */
    public function getTableName()
    {
        show_error('You must override this method getTableName() at models.');
    }

    /**
     * Get the primary key of table
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        show_error('You must override this method getPrimaryKey() at models.');
    }

    /**
     * Defined the attributes map its name of table
     */
    public function getAttributes()
    {
        return array(
           /* 'user_id' => '用户ID' */
        );
    }

    /**
     * Get one query result as array by condition
     *
     * @param array|stirng|NULL $select            
     * @param array|NULL $where            
     * @return array
     */
    public function find($select = NULL, $where = NULL)
    {
        $result = $this->findAll($select, $where, NULL, 1);
        return isset($result[0]) ? $result[0] : NULL;
    }

    /**
     * Get one query result by primary key as array
     *
     * @param int $pk            
     * @param array|string|NULL $select            
     * @return array
     */
    public function findByPk($pk, $select = NULL)
    {
        return $this->find($select, array(
            $this->getPrimaryKey() => $pk
        ));
    }

    /**
     * Get one query result by attributes
     *
     * @param array $attributes            
     * @return array
     */
    public function findByAttributes(array $attributes = array())
    {
        return $this->find(NULL, $attributes);
    }

    /**
     * Get all query results by attributes
     *
     * @param array $attributes            
     * @return array
     */
    public function findAllByAttributes(array $attributes = array())
    {
        return $this->findAll(NULL, $attributes);
    }

    /**
     * Generate select sql
     * @param string|array $select
     * @return void
     */
    protected function _select($select)
    {
        if ($select) {
            $select = (array) $select;
            $this->db->select(implode(',', $select));
        }
    }

    /**
     * Generate where sql
     * @param string|array $where
     * @return void
     */
    protected function _where($where)
    {
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                if(is_int($key) && is_string($value)) {
                    $this->db->where($value); //support string where: ['id>1',"name LIKE '%DAVE%'"]
                    continue;
                }
                if (is_array($value))
                    $this->db->where_in($key, $value);
                else
                    $this->db->where($key, $value);
            }
        } elseif (is_string($where)) //support string where
            $this->db->where($where);
    }

    /**
     * Generate group by sql
     * @param array|string $groupby 'field1', ['field1', 'field2']
     * @return void
     */
    protected function _groupby($groupby)
    {
        if (is_array($groupby)) {
            $this->db->order_by(array_values($groupby));
        } elseif (is_string($groupby))
            $this->db->group_by($groupby);
    }
    
    /**
     * Generate order by sql
     * @param array $orderby
     * @return void
     */
    protected function _orderby($orderby)
    {
        if (is_array($orderby)) {
            if(isset($orderby[0], $orderby[1]))
                $this->db->order_by($orderby[0], $orderby[1]);
            else
                foreach ($orderby as $key => $value)
                    $this->db->order_by($key, $value);
        }
    }

    /**
     * Generate limit sql
     * @param array|int $limit uint limit, ['uint offset', 'uint limit'], ['uint limit'] 
     * @return void
     */
    protected function _limit($limit)
    {
        if (is_array($limit)) {
            $count = count($limit);
            if ($count === 1)
                if (isset($limit[0]) && is_numeric($limit[0]))
                    $this->db->limit($limit[0]);
            elseif ($count >= 2) {
                if (isset($limit[0]) && isset($limit[1]) && is_numeric($limit[0]) && is_numeric($limit[1]) && $limit[0] >= 0 && $limit[1] >= 1)
                    $this->db->limit($limit[1], $limit[0]);
            }
        } elseif (is_numeric($limit))
            $this->db->limit($limit);
    }

    /**
     * Query all rows of the sql result
     * @param string|NULl $tbAlias
     * @return array
     */
    public function _getAll($tbAlias = NULL)
    {
        $rs = $this->db->get($this->getTableName() . ($tbAlias ? ' ' . (string)$tbAlias : ''));
        return $rs ? $rs->result_array() : array();
    }

    /**
     * Query one row of the sql result
     * @param string|NULl $tbAlias
     * @return array|NULL
     */
    public function _getRow($tbAlias = NULL)
    {
        $rs = $this->db->get($this->getTableName() . ($tbAlias ? ' ' . (string)$tbAlias : ''));
        return $rs ? $rs->row_array() : NULL;
    }

    /**
     * Get query arrays by condition
     *
     * @param array|string|NULL $select 'id,name', ['id', 'name']
     * @param array|string|NULL $where ['id' => 1], 'id=1', ['id', [1,2,3]]
     * @param array|NULL $orderby  ['key1'=>'asc'], ['key1', 'asc']
     * @param array|int|NULL $limit uint limit, ['uint offset', 'uint limit'], ['uint limit'] 
     * @return array
     */
    public function findAll($select = NULL, $where = NULL, $orderby = NULL, $limit = NULL)
    {
        $this->_select($select);

        $this->_where($where);

        $this->_orderby($orderby);
        
        $this->_limit($limit);
        
        return $this->_getAll();
    }

    /**
     * Insert one record to DB from array or object
     *
     * @param array|object $attributes
     *            key=>value array or member property=>value object
     */
    public function insert($attributes)
    {
        return $this->db->insert($this->getTableName(), $attributes);
    }

    /**
     * Batch insert records to DB from two-dimension array
     *
     * @param array $attributes
     *            two-dimension
     *            key=>value array
     */
    public function insertBatch(array $attributes)
    {
        return $this->db->insert_batch($this->getTableName(), $attributes);
    }

    /**
     * Update records by primary key
     *
     * @param int $pk            
     * @param array $attributes            
     */
    public function updateByPk($pk, array $attributes)
    {
        return $this->updateAll($attributes, array(
            $this->getPrimaryKey() => $pk
        ));
    }

    /**
     * Update records by condition
     *
     * @param array $attributes            
     * @param array $where|NULL            
     */
    public function updateAll(array $attributes, array $where = NULL)
    {
        $this->db->set($attributes);
        $this->_where($where);
        return $this->db->update($this->getTableName());
    }

    /**
     * Replace records
     *
     * @param array $attributes            
     * @param array $where|NULL            
     */
    public function replace(array $attributes)
    {
        return $this->db->replace($this->getTableName(), $attributes);
    }

    /**
     * Delete records by condition
     *
     * @param array $where|NULL            
     */
    public function deleteAll(array $where = NULL)
    {
        $this->_where($where);
        return $this->db->delete($this->getTableName());
    }

    /**
     * Delete records by primary key
     *
     * @param int $pk            
     */
    public function deleteByPk($pk)
    {
        return $this->deleteAll(array(
            $this->getPrimaryKey() => $pk
        ));
    }

    /**
     * Get the count by condition
     *
     * @param array $where            
     * @return int
     */
    public function count(array $where = NULL)
    {
        $this->_where($where);
        $this->db->from($this->getTableName());
        return floatval($this->db->count_all_results());
    }

    /**
     * Get the count by pk
     *
     * @param int $pk            
     * @return int
     */
    public function countByPk($pk)
    {
        return $this->count(array(
            $this->getPrimaryKey() => $pk
        ));
    }

    /**
     * Get the max by condition
     *
     * @param array $where            
     * @return float
     */
    public function max($field, array $where = NULL)
    {
        $this->db->select_max($field, 'CI_MAX');
        $this->_where($where);
        $result = $this->_getRow();
        return floatval($result['CI_MAX']);
    }

    /**
     * Get the min by condition
     *
     * @param array $where            
     * @return float
     */
    public function min($field, array $where = NULL)
    {
        $this->db->select_min($field, 'CI_MIN');
        $this->_where($where);
        $result = $this->_getRow();
        return floatval($result['CI_MIN']);
    }

    /**
     * Get the sum by condition
     *
     * @param array $where            
     * @return float
     */
    public function sum($field, array $where = NULL)
    {
        $this->db->select_sum($field, 'CI_SUM');
        $this->_where($where);
        $result = $this->_getRow();
        return floatval($result['CI_SUM']);
    }

    /**
     * Get the average by condition
     *
     * @param array $where            
     * @return float
     */
    public function avg($field, array $where = NULL)
    {
        $this->db->select_avg($field, 'CI_AVG');
        $this->_where($where);
        $result = $this->_getRow();
        return floatval($result['CI_AVG']);
    }

    /**
     * Execute $field=$field+$number
     *
     * @param string $field            
     * @param float $number            
     * @param array|string $where            
     * @param boolean $and            
     * @return boolean
     */
    public function increase($field, $number, $where = NULL, $and = TRUE)
    {
        $whereSQL = '';
        $params = array(
            floatval($number)
        );
        if (is_array($where)) {
            $count = count($where);
            $i = 0;
            foreach ($where as $key => $value) {
                if ($this->db->_has_operator($key)) {
                    // $value = $this->db->escape($value);
                    $whereSQL .= "`{$key}`{$value}";
                } else {
                    $whereSQL .= "`{$key}`=?";
                    $params[] = $value;
                }
                ++ $i;
                if ($i != $count) {
                    if ($and) {
                        $whereSQL .= ' AND ';
                    } else {
                        $whereSQL .= ' OR ';
                    }
                }
            }
        } elseif (is_string($where)) {
            $whereSQL = $where;
        }
        
        $sql = "UPDATE `{$this->getTableName()}` SET `{$field}`=`{$field}`+?";
        
        if ($whereSQL !== '') {
            $sql .= " WHERE {$whereSQL}";
        } else {
            $sql .= " WHERE 1";
        }
        
        return $this->db->query($sql, $params);
    }

    /**
     * Execute $field=$field-$number
     *
     * @param string $field            
     * @param float $number            
     * @param array|string $where            
     * @param boolean $and            
     * @return boolean
     */
    public function decrease($field, $number, $where = NULL, $and = TRUE)
    {
        $whereSQL = '';
        $params = array(
            floatval($number)
        );
        if (is_array($where)) {
            $count = count($where);
            $i = 0;
            foreach ($where as $key => $value) {
                if ($this->db->_has_operator($key)) {
                    // $value = $this->db->escape($value);
                    $whereSQL .= "`{$key}`{$value}";
                } else {
                    $whereSQL .= "`{$key}`=?";
                    $params[] = $value;
                }
                ++ $i;
                if ($i != $count) {
                    if ($and) {
                        $whereSQL .= ' AND ';
                    } else {
                        $whereSQL .= ' OR ';
                    }
                }
            }
        } elseif (is_string($where)) {
            $whereSQL = $where;
        }
        
        $sql = "UPDATE `{$this->getTableName()}` SET `{$field}`=`{$field}`-?";
        
        if ($whereSQL !== '') {
            $sql .= " WHERE {$whereSQL}";
        } else {
            $sql .= " WHERE 1";
        }
        
        return $this->db->query($sql, $params);
    }

    /**
     * Execute SQL and return a row as array
     *
     * @param string $sql            
     * @param array $param            
     * @return array
     */
    public function query($sql, array $param = array())
    {
        return $this->db->query($sql, $param)->row_array();
    }

    /**
     * Execute SQL and return all rows as array
     *
     * @param string $sql            
     * @param array $param            
     * @return array
     */
    public function queryAll($sql, array $param = array())
    {
        return $this->db->query($sql, $param)->result_array();
    }

    /**
     * Begin transaction
     */
    public function transBegin()
    {
        return $this->db->trans_begin();
    }

    /**
     * Commit transaction
     */
    public function transCommit()
    {
        return $this->db->trans_commit();
    }

    /**
     * Rollback transaction
     */
    public function transRollback()
    {
        return $this->db->trans_rollback();
    }

    /**
     * Get the status of transaction
     */
    public function transStatus()
    {
        return $this->db->trans_status();
    }
}