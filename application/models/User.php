<?php

class User extends MY_Model
{
    /**
     * Get the table name
     */
    public function getTableName()
    {
        return 'user';
    }

    /**
     * Get the primary key of table
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'user_id';
    }
}