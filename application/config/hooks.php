<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'] = array(
		'class'		=> 'AppHook',
		'function'	=> 'verifyPermission',
		'filename'	=> 'AppHook.php',
		'filepath'	=> 'hooks',
		'params'	=>  NULL
);

$hook['post_controller'] = array(
		'class'		=> 'AppHook',
		'function'	=> 'trackUser',
		'filename'	=> 'AppHook.php',
		'filepath'	=> 'hooks',
		'params'	=>  NULL
);



/* End of file hooks.php */
/* Location: ./application/config/hooks.php */