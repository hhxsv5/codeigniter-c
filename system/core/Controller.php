<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
	
	
	const CONTENT_TYPE_TEXT = 'text';
	const CONTENT_TYPE_HTML = 'html';
	const CONTENT_TYPE_JSON = 'json';
	const CONTENT_TYPE_XML 	= 'xml';
	const CONTENT_TYPE_XLS 	= 'xls';
	const CONTENT_TYPE_DOC 	= 'doc';
	
	public static $ContentTypes = array(
			self::CONTENT_TYPE_TEXT,
			self::CONTENT_TYPE_HTML,
			self::CONTENT_TYPE_JSON,
			self::CONTENT_TYPE_XML,
			self::CONTENT_TYPE_XLS,
			self::CONTENT_TYPE_DOC
	);
	public function setContentType($type = self::CONTENT_TYPE_HTML)
	{
		if(in_array($type, self::$ContentTypes)){
			$this -> output -> set_content_type($type);
		}
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */