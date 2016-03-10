<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_Controller // 继承MY_Controller 非CI_Controller
{

    public function __construct()
    {
        if (ENVIRONMENT === 'production')
            show_404();
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function outputTest()
    {
        echo 'Some contents<br>';
        $this->output->append_output('Append content1 !<br>'); // set_output之前append是无效的，会被set覆盖
        $this->output->set_output('I am final content!<br>');
        $this->output->append_output('Append content2 !<br>');
    }

    public function outputJsonTest()
    {
        $this->output->output_json([
            'xxxx' => 'dddd'
        ]);
    }

    public function requestTest()
    {
        var_dump($this->getRequest()->getQuery('get'), $this->getRequest()->getPost('post'), $this->getRequest()->getIPAddress());
    }

    public function test()
    {
        // 传统方式加载视图 需手动load头尾等布局
        /*
         * $this->load->view('test', array(
         * 'now' => date('Y-m-d H:i:s')
         * ));
         */
        $this->setPageTitle('Test');
        $this->setPageDescription('Test');
        $this->setPageKeywords('Dave');
        
        // 推荐方式加载头尾 包含头尾布局
        $this->render('test', array(
            'now' => date('Y-m-d H:i:s')
        ));
    }

    public function dbTest()
    {
        $this->load->model('user');
        $users = $this->user->findAll();
        var_dump($users);
    }

    public function sessionTest()
    {
        $this->load->library('session');
        // $this->session->uid = 100;
        var_dump($this->session->uid);
    }
}
