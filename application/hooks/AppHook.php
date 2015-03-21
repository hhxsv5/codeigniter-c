<?php
class AppHook
{
	private $_CI;

	public function __construct()
	{
		$this -> _CI = & get_instance();
	}

	public function verifyPermission($param)
	{
	}

	public function trackUser($param)
	{
		// do nothing now
	}
}