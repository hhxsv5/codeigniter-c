<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 自定义路由类
 *
 * 让CI控制器支持多级目录
 *
 * @author      SOHOCN.NET
 * @copyright   Copyright © 2012 - 2018 www.sohocn.net All rights reserved.
 * @created     2012-12-13
 * @updated     2012-12-13
 * @version     1.0
 */
 
class MY_Router extends CI_Router
{
	/**
	 *  Set the directory name
	 *
	 * @access  public
	 * @param   string
	 * @return  void
	 */
	function set_directory($dir)
	{
		$this->directory = $dir.'/';
	}
 
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access  private
	 * @param   array
	 * @return  array
	 */
 
	function _validate_request($segments)
	{
		if (count($segments) == 0)
		{
			return $segments;
		}
 
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
		{
			return $segments;
		}
 
		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			$temp = array('dir' => array(), 'path' => APPPATH.'controllers/');
 
			foreach($segments as $k => $v)
			{
				$temp['path'] .= $v.'/';
 
				if(is_dir($temp['path']))
				{
					$temp['dir'][] = $v;
					unset($segments[$k]);
				}
			}
 
			$this->set_directory(implode('/', $temp['dir']));
			$segments = array_values($segments);
			unset($temp);
 
			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$x = explode('/', $this->routes['404_override']);
 
						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');
 
						return $x;
					}
					else
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
			}
			else
			{
				// Is the method being specified in the route?
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);
 
					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				}
 
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php'))
				{
					$this->directory = '';
					return array();
				}
 
			}
 
			return $segments;
		}
 
 
		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class.  We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);
 
			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');
 
			return $x;
		}
 
 
		// Nothing else to do at this point but show a 404
		show_404($segments[0]);
	}
}
// END MY_Router Class