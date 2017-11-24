<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 支持XxxController的类名
 *
 * @author Dave Xie <hhxsv5@sina.com>
 */
class TestController extends CI_Controller
{

    public function __construct()
    {
        if (ENVIRONMENT === 'production')
            show_404();
        parent::__construct();
    }

    public function index()
    {
        echo __METHOD__;
    }

    public function actionTest()
    {
        echo __METHOD__;
    }
    //...
}
