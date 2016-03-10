<?php
/**
 * Created by PhpStorm.
 * User: bruce yang
 * Date: 2015/11/5
 * Time: 21:53
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$config['upload_path'] = 'assets/media/catalog/headimg/'; //上传文件的存放地点
$config['allowed_types'] = 'gif|jpg|png|jpeg';//允许的类型
$config['max_size'] = 1024; //图片最大的上传大小
$config['max_width'] = 800;//最宽
$config['max_height'] = 600;//最高
$config['overwrite'] = "TRUE"; //是否覆盖同文件名的文件
$config['encrypt_name'] = "TRUE"; //文件名生产随机的字符串