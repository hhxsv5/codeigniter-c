<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Welcome extends CI_Controller
{
	//Fixed: construct by hand to avoid same names both class and method
	//Modified by: hhxsv5@sina.com
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * http://example.com/index.php/welcome
	 * - or -
	 * http://example.com/index.php/welcome/index
	 * - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 *
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		echo '<h1>Welcome to CodeIgniter framework. </h1>', '<h2>', date('Y-m-d H:i:s'), '</h2>';
	}
	
	//Fixed: construct by hand to avoid same names both class and method
	//Modified by: hhxsv5@sina.com
	public function welcome()
	{
		echo 'Same names both controller & action.';
	}
}