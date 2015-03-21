<?php
final class Track_log
{
}
class Track_log_model extends Base_model
{
	// Must define the table name
	const TABLE_NAME		= 'track_log';
	const FIELD_URL			= 'url';
	const FIELD_QUERY		= 'query';
	const FIELD_CODE		= 'code';
	const FIELD_MSG			= 'msg';
	const FIELD_LOG_TIME 	= 'log_time';

	/**
	 * Must implements this function to tell parent class your table name
	 *
	 * @see Base_model::getTableName()
	 */
	public function getTableName()
	{
		return self::TABLE_NAME;
	}

	public function add($url, $query = '', $code = 0, $msg = '')
	{
		return parent::addOne(array(
				self::FIELD_URL => $url,
				self::FIELD_QUERY => $query,
				self::FIELD_CODE => intval($code),
				self::FIELD_MSG => $msg,
				self::FIELD_LOG_TIME => date('Y-m-d H:i:s')
		));
	}
}