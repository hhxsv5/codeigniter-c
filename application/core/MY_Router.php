<?php

/**
 * 扩展路由类
 * 1. 已支持XxxController这种方式命名控制器，路由: /目录/xxx。控制器名优先级：Class Test > Class TestController
 * 2. 支持actionYyy这种方式命名方法，路由: /目录/控制器/yyy。方法名优先级：public function doSomething() > public function actionDoSomething()
 * 
 * @author Dave Xie <hhxsv5@sina.com>
 */
class MY_Router extends CI_Router
{

    /**
     * 设置类名，支持控制器类名格式XxxController
     *
     * {@inheritDoc}
     *
     * @see CI_Router::set_class()
     */
    public function set_class($class)
    {
        parent::set_class($class);
        $this->class = ucfirst($this->class);
        
        $classPath = APPPATH . 'controllers/' . $this->directory;
        if (! file_exists($classPath . $this->class . '.php') AND file_exists($classPath . $this->class . 'Controller.php'))
            $this->class .= 'Controller';
    }

    /**
     * 设置请求Action，支持方法名格式actionYyy
     *
     * {@inheritDoc}
     *
     * @see CI_Router::set_method()
     */
    public function set_method($method)
    {
        parent::set_method($method);
        
        $classPath = APPPATH . 'controllers/' . $this->directory . $this->class . '.php';
        if (! file_exists($classPath))
            return;
            
        // Load the base controller class
        require BASEPATH . 'core/Controller.php';
        
        // Load the my controller class
        $myController = APPPATH . 'core/' . config_item('subclass_prefix') . 'Controller.php';
        if (file_exists($myController))
            require $myController;
        
        // Load real controller
        require $classPath;
        
        $methods = array_map('strtolower', get_class_methods($this->class));
        if (! in_array(strtolower($this->method), $methods, TRUE) AND in_array(strtolower('action' . $this->method), $methods, TRUE))
            $this->method = 'action' . ucfirst($this->method);
    }
}