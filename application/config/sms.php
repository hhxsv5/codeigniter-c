<?php
/**
 * Created by PhpStorm.
 * User: CxC
 * Date: 2015/11/3
 * Time: 17:20
 */

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 发送短信请求的url
 */
$config['base_url'] = 'http://api.smsbao.com/sms';
/**
 * 帐号
 */
$config['username'] = 'cqxnkj';
/**
 * 密码
 */
$config['password'] = '46741456asd';
/**
 * 签名
 */
$config['sign']     = '【新诺科技】';
/**
 * 签名前置或后置 prefix/suffix
 */
$config['location'] = 'prefix';