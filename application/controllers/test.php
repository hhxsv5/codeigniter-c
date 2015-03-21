<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Test extends CI_Controller
{
	
	public function testPDO()
	{
		$this -> load -> database('pdo_test');
		$sql = 'select * from test where 1';
		$res = $this -> db -> query($sql);
		//!!!Only fetch once for PDO
		var_dump($res -> result(), $res -> result_array(), $res -> num_rows());
	}

	public function testRequest()
	{
		echo '<pre>';
		$request = array (
				'GET' => $_GET, 
				'POST' => $_POST, 
				'FILE' => $_FILES, 
				'COOKIE' => $_COOKIE
		);
		var_dump($request);
		echo '</pre>';
	}

	public function testTrackLog()
	{
		echo $a;
		echo $a / 0;
	}
}